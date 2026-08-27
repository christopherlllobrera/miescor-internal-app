<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Employee extends Model
{
    use LogsActivity;

    protected $fillable = [
        'EmpNo',
        'EmpLName',
        'EmpFName',
        'EmpMName',
        'EmpAddress',
        'PostNo',
        'CompNo',
        'DeptNo',
        'LocNo',
        'EmpContact1',
        'EmpContact2',
        'EmpContact3',
        'EmpEmergency',
        'EmpEmerContact',
        'EmpEmailAd',
        'PictName',
        'ItemPict',
        'Gender',
        'TINNo',
        'SSSNo',
        'PAGIBIGNo',
        'PHILHEALTHNo',
        'EmpStatusNo',
        'StatusNo',
        'CivilNo',
        'MedCardNo',
        'MedCardPolicyNo',
        'DateHired',
        'BirthDate',
        'RegularizationDate',
        'CreatedBy',
        'DateCreated',
        'UpdatedBy',
        'DateUpdated',
    ];

    protected $table = 'tblEmployee';

    protected $primaryKey = 'EmpNo';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    // Activity Logs
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Employee')
            ->setDescriptionForEvent(fn (string $event) => "Employee has been {$event}")
            ->logAll()
            ->logOnlyDirty();
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->EmpLName}, {$this->EmpFName} {$this->EmpMName}";
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'PostNo', 'PostNo');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'DeptNo', 'DeptNo');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'LocNo', 'LocNo');
    }

    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class, 'empNo', 'EmpNo');
    }
}
