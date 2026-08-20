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
                                        $deptGroup = substr($employee->DeptNo, 0, 4);
                                        $departmentModule = DepartmentModule::where('cms_department_name', 'like', $deptGroup.'%')->first();

                                        return $departmentModule ? $departmentModule->id : null;
                                    }
                                }

                                return null;
                            })
                            ->disabled(fn () => auth()->user()->hasRole('Department PIC'))
                            ->options(function () {
                                // preload departments to avoid N+1 queries
                                $departments = Department::all()->keyBy('DeptNo');

                                return DepartmentModule::query()
                                    ->select('id', 'cms_department_name')
                                    ->get()
                                    ->mapWithKeys(function ($module) use ($departments) {
                                        $dept = $departments[$module->cms_department_name] ?? null;

                                        if (! $dept) {
                                            return [];
                                        }
                                        $words = explode(' ', $dept->DeptDesc);
                                        $formatted = collect($words)->map(function ($word, $index) {
                                            $word = strtolower($word);
                                            $smallWords = ['and', 'or', 'of', 'the', 'in', 'on', 'at', 'to', 'for'];
                                            if ($index > 0 && in_array($word, $smallWords)) {
                                                return $word;
                                            }
                                            if (strlen($word) <= 3) {
                                                return strtoupper($word);
                                            }

                                            return ucfirst($word);
                                        })->join(' ');

                                        return [
                                            $module->id => $formatted,
                                        ];
                                    })
                                    ->toArray();
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
