<?php

namespace App\Filament\Resources\DataManagement\Employees\Schemas;

use App\Models\Position;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Personal Information')
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 3,
                        'xl' => 3,
                        '2xl' => 3,
                    ])
                    ->columnSpan([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->description('Basic details about the employee.')
                    ->schema([
                        TextInput::make('EmpNo')
                            ->label('Employee No.')
                            ->required()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('EmpNo', strtoupper($state))),
                        TextInput::make('EmpLName')->label('Last Name')->required()->maxLength(50),
                        TextInput::make('EmpFName')->label('First Name')->required()->maxLength(50),
                        TextInput::make('EmpMName')->label('Middle Name')->maxLength(50),
                        Select::make('Gender')->options([
                            'MALE' => 'Male',
                            'FEMALE' => 'Female',
                        ]),
                        Select::make('CivilNo')
                            ->label('Civil Status')
                            ->options([
                                1 => 'Single',
                                2 => 'Married',
                                3 => 'Widowed',
                                4 => 'Separated',
                            ]),
                        DatePicker::make('BirthDate')->label('Birth Date'),
                    ]),

                Section::make('Employment Details')
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
                    ->description('Company-related information.')
                    ->schema([
                        Select::make('CompNo')
                            ->label('Company / Business Unit')
                            ->relationship('businessUnit', 'BusinessUnitDesc')
                            ->searchable()
                            ->preload(),

                        Select::make('LocNo')
                            ->label('Location')
                            ->relationship('location', 'LocDesc')
                            ->searchable()
                            ->preload(),

                        Select::make('DeptNo')
                            ->label('Department')
                            ->relationship('department', 'DeptDesc')
                            ->searchable()
                            ->preload(),

                        Select::make('PostNo')
                            ->label('Position')
                            ->options(function (callable $get) {
                                $deptNo = $get('DeptNo');
                                if (! $deptNo) {
                                    return [];
                                }

                                return Position::where('DeptNo', $deptNo)
                                    ->pluck('PostDesc', 'PostNo');
                            })
                            ->searchable()
                            ->preload(),

                        Select::make('EmpStatusNo')
                            ->label('Employment Status')
                            ->options(function () {
                                $fromDb = DB::table('tblEmpStatus')->pluck('EmpStatusDesc', 'EmpStatusNo')->toArray();
                                if (! empty($fromDb)) {
                                    return $fromDb;
                                }

                                return [
                                    1 => 'Regular',
                                    2 => 'Probationary',
                                    3 => 'Contractual',
                                    4 => 'Project-Based',
                                ];
                            }),

                        Select::make('StatusNo')
                            ->label('Employee Status')
                            ->options([
                                7 => 'Active',
                                8 => 'Inactive',
                                1 => 'Pending',
                            ]),

                        DatePicker::make('DateHired')->label('Date Hired'),
                        DatePicker::make('RegularizationDate')->label('Regularization Date'),
                    ]),

                Section::make('Contact Information')
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
                        Textarea::make('EmpAddress')->label('Address')->maxLength(500)->columnspanFull(),
                        TextInput::make('EmpContact1')->label('Contact No. 1')->integer(),
                        TextInput::make('EmpContact2')->label('Contact No. 2')->integer(),
                        TextInput::make('EmpContact3')->label('Contact No. 3')->integer(),
                        TextInput::make('EmpEmailAd')->label('Email')->email(),
                    ])->columns(2),

                Section::make('Emergency Contact')
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
                        TextInput::make('EmpEmergency')->label('Contact Person'),
                        TextInput::make('EmpEmerContact')->label('Contact Number')->integer(),
                    ]),

                Section::make('Documents & IDs')
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
                        TextInput::make('TINNo')->label('TIN No.'),
                        TextInput::make('SSSNo')->label('SSS No.'),
                        TextInput::make('PAGIBIGNo')->label('Pag-IBIG No.'),
                        TextInput::make('PHILHEALTHNo')->label('PhilHealth No.'),
                        TextInput::make('MedCardNo')->label('Medical Card No.'),
                        TextInput::make('MedCardPolicyNo')->label('Medical Policy No.'),
                    ]),
            ]);
    }
}
