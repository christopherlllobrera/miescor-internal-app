<?php

namespace App\Filament\Clusters\FAQ\FAQModules\Schemas;

use App\Models\Department;
use App\Models\DepartmentModule;
use App\Models\Employee;
use App\Models\FAQTagModule;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class FAQModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Frequently Asked Question')
                    ->description('Use these fields to create a new Frequently Asked Question resource.
                                The Title should be the full question, and the Body must contain the complete answer.
                                Assign the relevant Category and use the toggle to control its visibility.')
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
                        TextInput::make('faq_title')
                            ->maxLength(255)
                            ->columnStart(1)
                            ->label('Title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('faq_slug', Str::slug($state));
                            }),
                        TextInput::make('faq_slug')
                            ->maxLength(255)
                            ->label('Slug')
                            ->disabled(),
                        RichEditor::make('faq_body')
                            // ->maxLength(255)
                            ->label('Body')
                            ->columnspanFull(),
                        Select::make('cms_department_id')
                            ->label('Department Name')
                            ->required()
                            ->disabledOn('edit')
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
                            ->disabled(fn () => auth()->user()->hasRole('Department PIC'))
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
                            }),
                        Select::make('faq_tag_id')
                            ->options(function (callable $get) {
                                // The department selected earlier in the same form
                                $deptId = $get('cms_department_id');
                                // If the user hasn't chosen a department yet, return an empty list
                                if (! $deptId) {
                                    return [];
                                }

                                // Pull the tags that belong to that department
                                return FAQTagModule::where('cms_department_id', $deptId)
                                    ->pluck('faq_tag_name', 'id')
                                    ->toArray();      // ← return plain array for Filament
                            })
                            ->preload()
                            ->searchable()
                            ->label('Tag')
                            ->required()
                            ->createOptionForm([
                                TextInput::make('faq_tag_name')
                                    ->label('Tag Name'),
                            ])
                            ->createOptionAction(function (Action $action) {
                                return $action
                                    ->modalHeading('Create Tag')
                                    ->modalSubmitActionLabel('Create Tag')
                                    ->modalWidth('lg');
                            })
                            ->createOptionUsing(function (array $data): int {
                                return FAQTagModule::create($data)->id;
                            }),
                        Toggle::make('faq_is_published')
                            ->label('is Published')
                            ->columnStart(1)
                            ->required(),
                    ]),
            ]);
    }
}
