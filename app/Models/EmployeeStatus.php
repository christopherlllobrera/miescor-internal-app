<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $EmpStatusNo
 * @property string|null $EmpStatusDesc
 * @property int|null $CreatedBy
 * @property Carbon|null $DateCreated
 * @property int|null $UpdatedBy
 * @property Carbon|null $DateUpdated
 */
class EmployeeStatus extends Model
{
    protected $table = 'tblEmpStatus';

    protected $primaryKey = 'EmpStatusNo';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'EmpStatusNo',
        'EmpStatusDesc',
        'CreatedBy',
        'DateCreated',
        'UpdatedBy',
        'DateUpdated',
    ];

    protected $casts = [
        'DateCreated' => 'datetime',
        'DateUpdated' => 'datetime',
    ];

    /** @var array<string, string> */
    public const array EMPLOYEE_GROUPS = [
        'Regular' => 'Regular',
        'Probationary' => 'Probationary',
        'Project Hire' => 'Project Hire',
        'Fixed Term' => 'Fixed Term',
        'Regular Work Pool' => 'Regular Work Pool',
        'Service Agreement' => 'Service Agreement',
        'Meralco Seconded' => 'Meralco Seconded',
    ];

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return static::EMPLOYEE_GROUPS;
    }
}
