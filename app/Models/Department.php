<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @property string $DeptNo
 * @property string|null $DeptDesc
 * @property int|null $CreatedBy
 * @property int|null $UpdatedBy
 * @property Carbon|null $DateCreated
 * @property Carbon|null $DateUpdated
 * @property-read User|null $createdBy
 * @property-read User|null $updatedBy
 */
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
            ->dontLogEmptyChanges();
    }

    public function tapActivity(Activity $activity, string $event): void
    {
        // Prevent DB error when model primary key is a non-integer string
        // (activity_log.subject_id is an integer column in this project).
        $activity->subject_id = null;
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'CreatedBy', 'id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UpdatedBy', 'id');
    }
}
