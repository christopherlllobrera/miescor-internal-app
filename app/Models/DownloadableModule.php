<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class DownloadableModule extends Model
{
    use LogsActivity;

    protected $fillable = [
        'cms_department_id',
        'form_title',
        'form_attachment',
        'form_icon',
    ];

    // protected $casts = [
    //     'form_attachment' => 'array',
    // ];

    // Activity Logs
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Downloadable')
            ->setDescriptionForEvent(fn (string $event) => "Downloadable has been {$event}")
            ->logOnly(['cms_department_id', 'form_title', 'form_icon', 'created_at', 'updated_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relationships
    public function department()
    {
        return $this->belongsTo(DepartmentModule::class, 'cms_department_id');
    }

    public function getDownloadUrlAttribute(): ?string
    {
        if (! $this->form_attachment) {
            return null;
        }

        $file = is_array($this->form_attachment)
            ? reset($this->form_attachment)
            : $this->form_attachment;

        return $file ? Storage::url($file) : null;
    }

    protected static function formatDepartmentName(string $desc): string
    {
        $words = explode(' ', $desc);

        return collect($words)->map(function ($word, $index) {
            $word = strtolower($word);

            $smallWords = ['and', 'or', 'of', 'the', 'in', 'on', 'at', 'to', 'for'];

            if ($index > 0 && in_array($word, $smallWords)) {
                return $word;
            }

            if (strlen($word) <= 3) {
                return strtoupper($word);
            }

            return ucfirst($word);
        })->join(' ');
    }
}
