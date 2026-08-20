<?php

namespace App\Services;

use App\Data\WorkflowItemDTO;
use App\Models\DepartmentModule;
use App\Models\WorkflowModule;
use App\Models\WorkflowTagModule;
use Illuminate\Support\Collection;

/**
 * WorkflowService - Handles all Workflow-related business logic
 */
class WorkflowService
{
    /**
     * Get all workflows for a department
     */
    public function getWorkflowsByDepartment(DepartmentModule $department): Collection
    {
        return WorkflowModule::query()
            ->where('workflow_is_published', true)
            ->where('cms_department_id', $department->id)
            ->with(['department', 'tag'])
            ->latest()
            ->get()
            ->map(fn (WorkflowModule $wf) => WorkflowItemDTO::fromModel($wf));
    }

    /**
     * Search workflows by department and query
     */
    public function searchWorkflows(DepartmentModule $department, ?string $query = null): Collection
    {
        $queryBuilder = WorkflowModule::query()
            ->where('workflow_is_published', true)
            ->where('cms_department_id', $department->id)
            ->with(['department', 'tag'])
            ->latest();

        if ($query && strlen(trim($query)) >= 2) {
            $search = trim($query);
            $queryBuilder->where(function ($q) use ($search) {
                $q->where('workflow_title', 'like', "%{$search}%")
                    ->orWhere('workflow_body', 'like', "%{$search}%");
            });
        }

        return $queryBuilder->get()
            ->map(fn (WorkflowModule $wf) => WorkflowItemDTO::fromModel($wf));
    }

    /**
     * Filter workflows by tag
     */
    public function getWorkflowsByTag(DepartmentModule $department, int $tagId): Collection
    {
        return WorkflowModule::query()
            ->where('workflow_is_published', true)
            ->where('cms_department_id', $department->id)
            ->where('workflow_tag_id', $tagId)
            ->with(['department', 'tag'])
            ->latest()
            ->get()
            ->map(fn (WorkflowModule $wf) => WorkflowItemDTO::fromModel($wf));
    }

    /**
     * Get workflow by slug
     */
    public function getWorkflowBySlug(DepartmentModule $department, string $slug): ?WorkflowItemDTO
    {
        $workflow = WorkflowModule::where('workflow_slug', $slug)
            ->where('cms_department_id', $department->id)
            ->where('workflow_is_published', true)
            ->with(['department', 'tag'])
            ->first();

        return $workflow ? WorkflowItemDTO::fromModel($workflow) : null;
    }

    /**
     * Get related workflows by tag
     */
    public function getRelatedWorkflows(WorkflowModule $workflow, int $limit = 5): Collection
    {
        return WorkflowModule::query()
            ->where('workflow_tag_id', $workflow->workflow_tag_id)
            ->where('id', '!=', $workflow->id)
            ->where('workflow_is_published', true)
            ->with(['tag'])
            ->take($limit)
            ->get()
            ->map(fn (WorkflowModule $wf) => WorkflowItemDTO::fromModel($wf));
    }

    /**
     * Get all tags for a department with workflow counts
     */
    public function getTagsWithCounts(DepartmentModule $department): Collection
    {
        return WorkflowTagModule::whereHas('workflows', function ($query) use ($department) {
            $query->where('workflow_is_published', true)
                ->where('cms_department_id', $department->id);
        })->get();
    }

    /**
     * Group workflows by tag name
     */
    public function groupWorkflowsByTag(Collection $workflows): array
    {
        return $workflows->groupBy(fn (WorkflowItemDTO $wf) => $wf->tagName ?? 'General')->toArray();
    }

    /**
     * Get total workflow count for a department
     */
    public function getWorkflowCount(DepartmentModule $department): int
    {
        return WorkflowModule::query()
            ->where('workflow_is_published', true)
            ->where('cms_department_id', $department->id)
            ->count();
    }
}
