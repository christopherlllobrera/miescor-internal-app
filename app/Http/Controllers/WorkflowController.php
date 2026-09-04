<?php

// filepath: app/Http/Controllers/WorkflowController.php

namespace App\Http\Controllers;

use App\Models\DepartmentModule;
use App\Models\WorkflowModule;
use App\Services\WorkflowService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkflowController extends Controller
{
    public function __construct(
        private readonly WorkflowService $workflowService
    ) {}

    /**
     * Display all workflows for a specific department
     * URL: /department/{department}/workflow
     */
    public function index(Request $request, string $department): View
    {
        $currentDepartment = DepartmentModule::where('cms_department_slug', $department)->firstOrFail();

        // Use service for search and filtering
        $workflows = $this->workflowService->searchWorkflows($currentDepartment, $request->search);

        // Filter by tag if provided
        if ($request->has('tag') && $request->tag) {
            $workflows = $this->workflowService->getWorkflowsByTag($currentDepartment, (int) $request->tag);
        }

        // Group workflows by tag
        $workflowsByTag = $this->workflowService->groupWorkflowsByTag($workflows);

        // Get tags for this department
        $tags = $this->workflowService->getTagsWithCounts($currentDepartment);

        return view('employee-portal.homepage.departments.workflow.workflowpage', compact('workflows', 'workflowsByTag', 'tags', 'currentDepartment'));
    }

    /**
     * Display a single workflow
     * URL: /department/{department}/workflow/{slug}
     */
    public function show(string $department, string $slug): View
    {
        $currentDepartment = DepartmentModule::where('cms_department_slug', $department)->firstOrFail();

        // Get the workflow using service
        $workflow = $this->workflowService->getWorkflowBySlug($currentDepartment, $slug);

        if (! $workflow) {
            abort(404);
        }

        // Get related workflows
        $workflowModel = WorkflowModule::find($workflow->id);
        $relatedWorkflows = $this->workflowService->getRelatedWorkflows($workflowModel);

        return view('employee-portal.homepage.departments.workflow.show', compact('workflow', 'relatedWorkflows', 'currentDepartment'));
    }
}
