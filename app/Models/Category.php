<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Category extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'title',
        'slug',
        'text_color',
        'bg_color',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Category')
            ->setDescriptionForEvent(fn (string $event) => "Category has been {$event}")
            ->logAll()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}
