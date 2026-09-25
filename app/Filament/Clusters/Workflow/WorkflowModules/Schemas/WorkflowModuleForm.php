<?php

namespace App\Filament\Clusters\Workflow\WorkflowModules\Schemas;

use App\Models\Department;
use App\Models\DepartmentModule;
use App\Models\Employee;
use App\Models\WorkflowTagModule;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class WorkflowModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Workflow Details')
                    ->description('Use these fields to create a new workflow resource.
                    The Title should clearly name the process, and the Body must contain all step-by-step instructions.
                    Assign the relevant Tags for proper categorization.')
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->columnSpan([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
                        TextInput::make('workflow_title')
                            ->label('Title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('workflow_slug', Str::slug($state));
                            }),
                        TextInput::make('workflow_slug')
                            ->label('Slug')
                            ->disabled(),
                        RichEditor::make('workflow_body')
                            ->label('Body')
                            ->required()
                            ->fileAttachmentsDirectory('attachments')
                            ->fileAttachmentsVisibility('private')
                            ->columns(3)
                            ->columnspanFull(),
                        Checkbox::make('workflow_is_published')
                            ->label('is Published')
                            ->columnspanFull(),
                        Select::make('cms_department_id')
                            ->label('Department Name')
                            ->required()
                            ->disabledOn('edit')
                            ->options(function () {
                                $user = auth()->user();
                                if ($user && $user->hasRole('Department PIC') && $user->empNo) {
                                    $employee = Employee::where('EmpNo', $user->empNo)->first();
                                    if ($employee && $employee->DeptNo) {
                                        $dept = Department::where('DeptNo', $employee->DeptNo)
                                            ->orWhere('CostCntrNo', $employee->DeptNo)
                                            ->first();
                                        $costCenter = $dept?->CostCntrNo ?? $employee->DeptNo;

                                        if ($costCenter) {
                                            $deptGroup = substr($costCenter, 0, 4);

                                            return DepartmentModule::where(function ($q) use ($deptGroup) {
                                                $q->where('cms_department_cost_center', 'like', $deptGroup.'%')
                                                    ->orWhere('cms_department_name', 'like', $deptGroup.'%');
                                            })->get()->pluck('display_name', 'id')->toArray();
                                        }
                                    }
                                }

                                return DepartmentModule::all()->pluck('display_name', 'id')->toArray();
                            })
                            ->dehydrated(true)
                            ->default(function () {
                                $user = auth()->user();
                                if ($user && $user->hasRole('Department PIC') && $user->empNo) {
                                    $employee = Employee::where('EmpNo', $user->empNo)->first();
                                    if ($employee && $employee->DeptNo) {
                                        $dept = Department::where('DeptNo', $employee->DeptNo)
                                            ->orWhere('CostCntrNo', $employee->DeptNo)
                                            ->first();
                                        $costCenter = $dept?->CostCntrNo ?? $employee->DeptNo;

                                        if ($costCenter) {
                                            $deptGroup = substr($costCenter, 0, 4);
                                            $departmentModule = DepartmentModule::where(function ($q) use ($deptGroup) {
                                                $q->where('cms_department_cost_center', 'like', $deptGroup.'%')
                                                    ->orWhere('cms_department_name', 'like', $deptGroup.'%');
                                            })->first();

                                            return $departmentModule ? $departmentModule->id : null;
                                        }
                                    }
                                }

                                return null;
                            })
                            ->disabled(fn () => auth()->user()->hasRole('Department PIC')),
                        Select::make('workflow_tag_id')
                            ->label('Tag')
                            // ->required()
                            ->options(function (callable $get) {
                                // The department selected earlier in the same form
                                $deptId = $get('cms_department_id');
                                // If the user hasn't chosen a department yet, return an empty list
                                if (! $deptId) {
                                    return [];
                                }

                                // Pull the tags that belong to that department
                                return WorkflowTagModule::where('cms_department_id', $deptId)
                                    ->pluck('workflow_tag_name', 'id')
                                    ->toArray();      // ← return plain array for Filament
                            })
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('workflow_tag_name')
                                    ->label('Tag Name'),
                            ])
                            ->createOptionAction(function (Action $action) {
                                return $action
                                    ->modalHeading('Create Tag')
                                    ->modalSubmitActionLabel('Create Tag')
                                    ->modalWidth('lg');
                            })
                            ->createOptionUsing(function (array $data): int {
                                return WorkflowTagModule::create($data)->id;
                            }),
                    ])
                    ->collapsible(),
            ]);
    }
}
