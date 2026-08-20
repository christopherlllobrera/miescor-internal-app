<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class WorkflowModule extends Model
{
    use LogsActivity;

    protected $fillable = [
        'cms_department_id',
        'workflow_tag_id',
        'workflow_title',
        'workflow_slug',
        'workflow_body',
        'workflow_is_published',
    ];

    protected $casts = [
        'workflow_is_published' => 'boolean',
    ];

    // Activity Logs
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Workflow')
            ->setDescriptionForEvent(fn (string $event) => "Workflow has been {$event}")
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relationships
    public function department()
    {
        return $this->belongsTo(DepartmentModule::class, 'cms_department_id');
    }

    public function tag()
    {
        return $this->belongsTo(WorkflowTagModule::class, 'id', 'cms_department_id');
    }

    public function departmentTags()
    {
        return $this->hasManyThrough(
            WorkflowTagModule::class,
            DepartmentModule::class,
            'id',                // DepartmentModule.id  ← foreign key on WorkflowTagModule
            'id',                // WorkflowTagModule.id ← local key on WorkflowModule
            'cms_department_id', // WorkflowModule.cms_department_id
            'id'                 // DepartmentModule.id
        )->whereColumn('workflow_tag_modules.cms_department_id', 'workflow_modules.cms_department_id');
    }
}
