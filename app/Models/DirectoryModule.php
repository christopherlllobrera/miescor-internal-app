<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class DirectoryModule extends Model
{
    use LogsActivity;

    protected $fillable = [
        'cms_department_id',
        'poc_name_id',
        'poc_job_position',
        'poc_image',
    ];

    // Activity Logs
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Directories')
            ->setDescriptionForEvent(fn (string $event) => "Directory has been {$event}")
            ->logOnly(['poc_name_id', 'poc_job_position', 'created_at', 'updated_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relationships
    public function department()
    {
        return $this->belongsTo(DepartmentModule::class, 'cms_department_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'poc_job_position');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'poc_name_id', 'EmpNo');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->EmpLName}, {$this->EmpFName} {$this->EmpMName}";
    }

    public function setPocImageAttribute($value)
    {
        $this->attributes['poc_image'] = $value;
    }

    public function getImageUrlAttribute(): string
    {
        if (! empty($this->poc_image) && is_string($this->poc_image)) {
            return 'data:image/jpeg;base64,'.base64_encode($this->poc_image);
        }

        return asset('images/placeholder.jpg');
    }
}
