<?php

namespace App\Models;

use App\Observers\ContractObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[ObservedBy(ContractObserver::class)]
class Contract extends Model
{
    use LogsActivity;

    protected $fillable = [
        'reference_no',
        'contract_title',
        'contract_description',
        'assigned_to',
        'contract_type',
        'status',
        'turnaround_days',
        'has_turnaround_time',
        'turnaround_date',
        'deadline',
        'remarks',
        'proponent',
        'position',
        'created_by',
        'updated_by',
        'attachment',
    ];

    protected function casts(): array
    {
        return [
            'has_turnaround_time' => 'boolean',
            'turnaround_days' => 'integer',
            'turnaround_date' => 'date',
            'deadline' => 'date',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Contract')
            ->setDescriptionForEvent(fn (string $event) => "Contract has been {$event}")
            ->logAll()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_to', 'EmpLName');
    }

    public function assignees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'contract_assignees', 'contract_id', 'emp_no', 'id', 'EmpNo');
    }

    public function contractProponent(): BelongsTo
    {
        return $this->belongsTo(ContractProponent::class, 'proponent', 'proponent_code');
    }

    public function contractRemarks()
    {
        return $this->hasMany(ContractRemark::class);
    }
}
