<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Position extends Model
{
    use LogsActivity;

    protected $fillable = [
        'PostNo',
        'PostDesc',
        'DeptNo',
        'CreatedBy',
        'UpdatedBy',
        'DateCreated',
        'DateUpdated',
    ];

    protected $table = 'tblPosition';

    protected $primaryKey = 'PostNo';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->CreatedBy = auth()->user()->id ?? null;
            $model->DateCreated = now();
        });

        static::updating(function ($model) {
            $model->UpdatedBy = auth()->user()->id ?? null;
            $model->DateUpdated = now();
        });
    }

    // Activity Logs
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Position')
            ->setDescriptionForEvent(fn (string $event) => "Position has been {$event}")
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // A Post belongs to a Department
    public function department()
    {
        return $this->belongsTo(Department::class, 'DeptNo', 'DeptNo');
    }

    // A Post can have many Employees
    public function employees()
    {
        return $this->hasMany(Employee::class, 'PostNo', 'PostNo');
    }
}
