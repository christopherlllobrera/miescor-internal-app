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
 * @property int|null $BUNo
 * @property string|null $CostCntrNo
 * @property int|null $LocNo
 * @property int|null $CreatedBy
 * @property int|null $UpdatedBy
 * @property Carbon|null $DateCreated
 * @property Carbon|null $DateUpdated
 * @property-read BusinessUnits|null $businessUnit
 * @property-read Location|null $location
 * @property-read User|null $createdBy
 * @property-read User|null $updatedBy
 */
class Department extends Model
{
    use LogsActivity;

    protected $fillable = [
        'DeptNo',
        'DeptDesc',
        'BUNo',
        'CostCntrNo',
        'LocNo',
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
     * @return BelongsTo<BusinessUnits, $this>
     */
    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnits::class, 'BUNo', 'BusinessUnitNo');
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

    /**
     * @return BelongsTo<Location, $this>
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'LocNo', 'LocNo');
    }

    public function getLocationAttribute(): ?Location
    {
        if (! $this->LocNo) {
            return null;
        }

        return Location::where('LocNo', (string) $this->LocNo)
            ->orWhere('LocCode', "Code{$this->LocNo}")
            ->first();
    }
}
