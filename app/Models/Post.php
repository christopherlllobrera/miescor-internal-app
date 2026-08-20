<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Post extends Model
{
    use LogsActivity;
    use SoftDeletes;

    // protected static function booted(): void
    // {
    //     $clearCache = fn () => Cache::forget('featuredPosts');

    //     static::created($clearCache);
    //     static::updated($clearCache);
    //     static::deleted($clearCache);
    // }

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'image',
        'body',
        'published_at',
        'featured',
    ];

    // Activity Logs
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Post')
            ->logOnly(['user_id',
                'title',
                'slug',

                'body',
                'published_at',
                'featured', ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'user_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_like')->withTimestamps();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where(function (Builder $q) {
            $q->where('published_at', '<=', Carbon::now())
                ->orWhereNull('published_at');
        });
    }

    public function scopeWithCategory(Builder $query, string $category): Builder
    {
        return $query->whereHas('categories', function ($query) use ($category) {
            $query->where('slug', $category);
        });
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    public function scopePopular(Builder $query): Builder
    {
        return $query->withCount('likes')
            ->orderBy('likes_count', 'desc');
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        return $query->where('title', 'like', "%{$search}%");
    }

    public function getExcerpt(): string
    {
        return Str::limit(strip_tags($this->body), 150);
    }

    public function getReadingTime(): int
    {
        $mins = round(str_word_count($this->body) / 250);

        return ($mins < 1) ? 1 : $mins;
    }

    public function getThumbnailUrl(): string
    {
        $binary = $this->image;

        if (is_resource($binary)) {
            $binary = stream_get_contents($binary);
        }

        if (empty($binary)) {
            return asset('images/tower.jpg');
        }

        // If it's a standard URL (for seeded data or legacy files)
        if (strlen($binary) < 255 && str_contains($binary, 'http')) {
            return $binary;
        }

        // If it's a filename/path stored in storage
        if (strlen($binary) < 255 && Storage::disk('public')->exists($binary)) {
            return Storage::disk('public')->url($binary);
        }

        // Otherwise, treat as binary data to convert to base64
        $base64 = base64_encode($binary);

        if (class_exists(\finfo::class)) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($binary) ?: 'image/jpeg';
        } else {
            $magic = substr($binary, 0, 4);
            $mimeType = str_starts_with($magic, "\x89PNG") ? 'image/png' : 'image/jpeg';
        }

        return "data:{$mimeType};base64,{$base64}";
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
