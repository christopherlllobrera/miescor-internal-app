<?php

namespace App\Filament\Clusters\FAQ\FAQTagModules\Schemas;

use App\Models\DepartmentModule;
use App\Models\Employee;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FAQTagModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Frequently Asked Question Tag')
                    ->description('Use tags to classify FAQs by topic. Each FAQ can be assigned to improve filtering and discoverability.')
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
                        Select::make('cms_department_id')
                            ->label('Department')
                            ->required()
                            ->relationship('department', 'cms_department_slug')
                            ->searchable()
                            ->preload()
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
                            ->disabled(fn () => auth()->user()->hasRole('Department PIC')),
                        TextInput::make('faq_tag_name')
                            ->label('Tag Name'),
                    ]),
            ]);
    }
}
