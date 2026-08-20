<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class WorkflowTagModule extends Model
{
    use LogsActivity;

    protected $fillable = [
        'cms_department_id',
        'workflow_tag_name',
    ];

    // Activity Logs
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Workflow Tag')
            ->setDescriptionForEvent(fn (string $event) => "Workflow tag has been {$event}")
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relationships
    public function workflows()
    {
        return $this->hasMany(WorkflowModule::class, 'workflow_tag_id');
    }

    public function department()
    {
        return $this->belongsTo(DepartmentModule::class, 'cms_department_id');
    }
}
