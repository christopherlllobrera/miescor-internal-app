<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class DepartmentModule extends Model
{
    use LogsActivity;

    protected $fillable = [
        'cms_department',
        'cms_department_name',
        'cms_department_slug',
        'cms_department_description',
        'cms_banner',
        'cms_icon',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Department Page')
            ->setDescriptionForEvent(fn (string $event) => "Department page has been {$event}")
            ->logOnly(['cms_department', 'cms_department_name', 'cms_department_slug', 'cms_department_description', 'cms_icon', 'created_at', 'updated_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getRouteKeyName(): string
    {
        return 'cms_department_slug';
    }

    public function getIconComponentAttribute(): string
    {
        // If icon is stored as 'heroicon-o-briefcase', return it
        // If icon is stored as 'briefcase', convert to 'heroicon-o-briefcase'
        if ($this->cms_icon) {
            if (str_starts_with($this->cms_icon, 'heroicon-')) {
                return $this->cms_icon;
            }

            return 'heroicon-o-'.$this->cms_icon;
        }

        return 'heroicon-o-building-office-2'; // Default icon
    }

    // Relationships
    public function directories()
    {
        return $this->hasMany(DirectoryModule::class, 'cms_department_id');
    }

    public function downloadables()
    {
        return $this->hasMany(DownloadableModule::class, 'cms_department_id');
    }

    public function workflows()
    {
        return $this->hasMany(WorkflowModule::class, 'cms_department_id');
    }

    public function faqs()
    {
        return $this->hasMany(FAQModule::class, 'cms_department_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'cms_department_name', 'DeptNo');
    }

    public static function formatDepartmentName(string $deptNo): string
    {
        $dept = Department::where('DeptNo', $deptNo)->first();

        if (! $dept) {
            return $deptNo;
        }

        $words = explode(' ', $dept->DeptDesc);

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

    public static function formatString(string $string): string
    {
        $words = explode(' ', $string);
        $smallWords = ['and', 'or', 'of', 'the', 'in', 'on', 'at', 'to', 'for'];

        return collect($words)->map(function ($word, $index) use ($smallWords) {
            $word = strtolower($word);

            if ($index > 0 && in_array($word, $smallWords)) {
                return $word;
            }

            if (strlen($word) <= 3) {
                return strtoupper($word);
            }

            return ucfirst($word);
        })->join(' ');
    }

    public function getDisplayNameAttribute(): string
    {
        // If user typed a custom name in 'cms_department', use it
        if ($this->cms_department) {
            return $this->cms_department;
        }

        // Otherwise, find the DeptDesc from the related Department model and format it
        // Note: this uses your existing 'department' relationship
        if ($this->department) {
            return self::formatString($this->department->DeptDesc);
        }

        return $this->cms_department_name ?? 'Unnamed Department';
    }

    public function setPocImageAttribute($value)
    {
        $this->attributes['cms_banner'] = $value;
    }

    public function getImageUrlAttribute(): string
    {
        if (! empty($this->cms_banner) && is_string($this->cms_banner)) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($this->cms_banner) ?: 'image/jpeg';

            return 'data:'.$mimeType.';base64,'.base64_encode($this->cms_banner);
        }

        return asset('images/placeholder.jpg');
    }
}
