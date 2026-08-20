<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Department extends Model
{
    use LogsActivity;

    protected $fillable = [
        'DeptNo',
        'DeptDesc',
        'CreatedBy',
        'UpdatedBy',
        'DateCreated',
        'DateUpdated',
    ];

    protected $table = 'tblDepartment';

    protected $primaryKey = 'DeptNo';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $casts = [
        'DateCreated' => 'datetime',
        'DateUpdated' => 'datetime',
    ];

    // public function employees()
    // {
    //     return $this->hasMany(Employee::class, 'DeptNo', 'DeptNo');
    // }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Department')
            ->setDescriptionForEvent(fn (string $event) => "Department has been {$event}")
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function tapActivity(Activity $activity, string $event): void
    {
        // Prevent DB error when model primary key is a non-integer string
        // (activity_log.subject_id is an integer column in this project).
        $activity->subject_id = null;
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'CreatedBy', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'UpdatedBy', 'id');
    }
}
