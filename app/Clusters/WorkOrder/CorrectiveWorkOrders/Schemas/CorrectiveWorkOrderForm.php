<?php

namespace App\Filament\Clusters\WorkOrder\CorrectiveWorkOrders\Schemas;

use App\Models\Employee;
use App\Models\PreventiveWorkOrder;
use App\Models\Vehicles;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CorrectiveWorkOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Job Order Information')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Section::make('Job Order Details')
                                ->description('Enter the job order reference numbers and billing information')
                                ->icon('heroicon-o-clipboard-document-list')
                                ->collapsible()
                                ->schema([
                                    TextInput::make('job_order_no')
                                        ->label('Job Order No.')
                                        ->placeholder('e.g., CM-2025-00001') // CM-2025-XXXXX
                                        ->default(function () {
                                            $JobOrderNo = PreventiveWorkOrder::orderBy('job_order_no', 'desc')->first()?->job_order_no;
                                            if ($JobOrderNo) {
                                                $lastNumber = (int) Str::afterLast($JobOrderNo, '-');

                                                return 'CM-2025-'.str_pad(++$lastNumber, 6, '0', STR_PAD_LEFT);
                                            } else {
                                                return 'CM-2025-00000';
                                            }
                                        })
                                        ->unique(ignoreRecord: true)
                                        ->maxLength(255),
                                    TextInput::make('job_order_sap_no')
                                        ->label('SAP Job Order No.')
                                        ->unique(ignoreRecord: true)
                                        ->maxLength(255),
                                    TextInput::make('billing_invoice_no')
                                        ->label('Billing Invoice No.')
                                        ->unique(ignoreRecord: true)
                                        ->maxLength(255),
                                    TextInput::make('charge_account_no')
                                        ->label('Charge Account No.')
                                        ->placeholder('e.g., CA-MAINT-001')
                                        ->maxLength(255),
                                    Select::make('type')
                                        ->options([
                                            'Owned' => 'Owned',
                                            'Serviced' => 'Serviced',
                                        ])
                                        ->live(),
                                    Select::make('assignment')
                                        ->options([
                                            'Contracted' => 'Contracted',
                                            'In-house' => 'In-house',
                                            'Non-operational' => 'Non-operational',
                                            'Unassigned' => 'Unassigned',
                                        ])
                                        ->live()
                                        ->visible(fn (callable $get): bool => $get('type') === 'Owned'),
                                    TextInput::make('UCR_ref_no')
                                        ->label('UCR Ref. No.'),
                                    TextInput::make('UCR_amount')
                                        ->label('UCR Amount')
                                        ->numeric()
                                        ->prefix('₱'),
                                    TextInput::make('invoice')
                                        ->label('Invoice')
                                        ->numeric(),
                                ])->columns([
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
                                ]),
                            Section::make()
                                ->relationship('workOrder')
                                ->visible(fn (callable $get): bool => $get('assignment') === 'Contracted')->live()
                                ->schema([
                                    Select::make('company_id')->label('Company')
                                        ->options([
                                            'MLI' => 'MLI',
                                            'MBI' => 'MBI',
                                            'MIESCOR' => 'MIESCOR',
                                        ]),
                                    DatePicker::make('start_date')->label('Start Date'),
                                    DatePicker::make('end_date')->label('End Date'),
                                    TextInput::make('contract_amount')
                                        ->numeric()->label('Contract Amount'),
                                    Select::make('contact_person_name')->label('Contact Person')
                                        ->options(
                                            fn (): Collection => Employee::where('CompNo', 1103)
                                                ->whereNotNull('PostNo')
                                                ->get()
                                                ->mapWithKeys(fn ($employee) => [
                                                    $employee->EmpNo => $employee->full_name,
                                                ])
                                        )
                                        ->columnstart(1),
                                    TextInput::make('contact_person_email'
                                    )->label('Email')->email(),
                                    TextInput::make('contact_person_no')
                                        ->label('Contact No.')->prefix('+63')->numeric(),
                                ])->columns([
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
                                    'lg' => 3,
                                    'xl' => 3,
                                    '2xl' => 3,
                                ]),
                            Section::make('Vehicle Information')
                                ->description('Select the vehicle and provide location details. Vehicle type will be automatically populated based on the selected vehicle.')
                                ->icon('heroicon-o-truck')
                                ->schema([
                                    Select::make('plate_no_id')
                                        ->label('Vehicle Plate Number')
                                        ->relationship('plateNo', 'plate_number')
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(function (callable $set, $state) {
                                            if ($state) {
                                                $vehicle = Vehicles::find($state);
                                                if ($vehicle) {
                                                    $set('vehicle_type', $vehicle->vehicle_type);
                                                }
                                            }
                                        })
                                        ->afterStateHydrated(function (callable $set, $state) {
                                            if ($state) {
                                                $vehicle = Vehicles::find($state);
                                                if ($vehicle) {
                                                    $set('vehicle_type', $vehicle->vehicle_type);
                                                }
                                            }
                                        }),
                                    TextInput::make('vehicle_type')
                                        ->label('Vehicle Type')
                                        ->disabled(),
                                    TextInput::make('vehicle_location')
                                        ->label('Vehicle Location')
                                        ->placeholder('e.g., MLI , Meralco')
                                        ->maxLength(255),
                                    TextInput::make('odometer_reading')
                                        ->label('Odometer Reading (km)')
                                        ->placeholder('e.g., 25000')
                                    // ->extraAttributes()
                                        ->numeric(),
                                ])
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
                                ]),
                            Section::make('Driver and Contact Person')
                                ->description('Enter the details of the assigned driver, contact person, and the requisitioning office for this job order.')
                                ->icon('heroicon-o-users')
                                ->collapsible()
                                ->schema([
                                    Select::make('driver_name_id')
                                        ->label('Driver')
                                        ->options(
                                            fn (): Collection => Employee::where('CompNo', 1103)
                                                ->whereNotNull('PostNo')
                                                ->get()
                                                ->mapWithKeys(fn ($employee) => [
                                                    $employee->EmpNo => $employee->full_name,
                                                ])
                                        )
                                        ->searchable()
                                        ->preload()
                                        ->required(),
                                    Select::make('requisition_office')
                                        ->label('Requisition Office')
                                        ->placeholder('e.g., MLI, MBI, MIESCOR, Individual, Others')
                                        ->options([
                                            'MLI' => 'MLI',
                                            'MBI' => 'MBI',
                                            'MIESCOR' => 'MIESCOR',
                                            'Individual' => 'Individual',
                                            'Others' => 'Others',
                                        ])
                                        ->suffixIcon('heroicon-o-building-office-2'),
                                    Select::make('contact_person_id')
                                        ->label('Contact Person')
                                        ->options(
                                            fn (): Collection => Employee::where('CompNo', 1102)
                                                ->whereNotNull('PostNo')
                                                ->get()
                                                ->mapWithKeys(fn ($employee) => [
                                                    $employee->EmpNo => $employee->full_name,
                                                ])
                                        )
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->live(),
                                    TextInput::make('contact_number')
                                        ->label('Contact Number')
                                        ->placeholder('e.g., 09123456789')
                                        ->prefix('+63')
                                        ->numeric()
                                        ->maxLength(255)
                                        ->suffixIcon('heroicon-o-phone'),

                                ])
                                ->columns([
                                    'default' => 1,
                                    'sm' => 2,
                                    'md' => 2,
                                    'lg' => 2,
                                    'xl' => 2,
                                    '2xl' => 2,
                                ])->columnSpan([
                                    'default' => 1,
                                    'sm' => 2,
                                    'md' => 2,
                                    'lg' => 2,
                                    'xl' => 2,
                                    '2xl' => 2,
                                ]),
                        ]),
                    Step::make('Problem Assessment')
                        ->icon('heroicon-o-exclamation-triangle')
                        ->schema([
                            Section::make('Trouble Report & Assessment')
                                ->description('Detailed documentation of vehicle problems and initial findings')
                                ->icon('heroicon-o-document-magnifying-glass')
                                ->collapsible()
                                ->schema([
                                    Textarea::make('vehicle_trouble_report')
                                        ->label('Vehicle Trouble Report')
                                        ->hint('To be filled by the driver')
                                        ->placeholder('Describe the vehicle problem in detail...')
                                        ->rows(4)
                                        ->columnSpanFull()
                                        ->required(),
                                    Textarea::make('initial_assessment')
                                        ->label('Initial Assessment')
                                        ->hint('To be filled by the mechanic')
                                        ->required()
                                        ->placeholder('Initial diagnosis and recommended actions...')
                                        ->rows(4)
                                        ->columnSpanFull(),
                                ]),
                        ]),
                    Step::make('Work Details')
                        ->icon('heroicon-o-wrench')
                    // ->description('Record actual work performed and time spent')
                        ->schema([
                            Section::make('Work Time Tracking')
                                ->description('Track time spent on different work activities')
                                ->icon('heroicon-o-clock')
                                ->collapsible()
                                ->schema([
                                    Repeater::make('actual_work_time')
                                        ->label('Actual Work Done')
                                        ->schema([
                                            Select::make('work_type')
                                                ->label('Work Type')
                                                ->options([
                                                    'diagnosis' => 'Diagnosis',
                                                    'repair' => 'Repair Work',
                                                    'maintenance' => 'Maintenance',
                                                    'testing' => 'Testing',
                                                    'inspection' => 'Inspection',
                                                    'parts_replacement' => 'Parts Replacement',
                                                    'cleaning' => 'Cleaning',
                                                    'other' => 'Other',
                                                ])
                                                ->required()
                                                ->native(false),
                                            DatePicker::make('date')
                                                ->label('Date')
                                                ->required()
                                                ->default(now()),

                                            TimePicker::make('start_time')
                                                ->label('Start Time')
                                                ->default(now())
                                                ->required()
                                                ->seconds(false),

                                            TimePicker::make('end_time')
                                                ->label('End Time')
                                                ->required()
                                                ->seconds(false),

                                            TextInput::make('technician_name')
                                                ->label('Technician/Mechanic')
                                                ->placeholder('Name of person who performed the work')
                                                ->maxLength(255),

                                            Textarea::make('work_description')
                                                ->label('Work Description or Remarks')
                                                ->placeholder('Describe the work performed...')
                                                ->rows(2),
                                        ])
                                        ->columns(1)
                                        ->itemLabel(fn (array $state): ?string => $state['work_type'] ? ucfirst(str_replace('_', ' ', $state['work_type'])) : null
                                        )
                                        ->addActionLabel('Add Work Time Entry')
                                        ->defaultItems(1)
                                        ->collapsible()
                                        ->cloneable(),
                                ]),
                            Section::make('Materials Used')
                                ->description('Keep a detailed list of all materials, parts, and supplies applied to this job order.')
                                ->icon('heroicon-o-cube-transparent')
                                ->collapsible()
                                ->schema([
                                    Repeater::make('issuance_of_materials')
                                        ->defaultItems(1)
                                        ->label('Issuance of Materials Used')
                                        ->schema([
                                            TextInput::make('STF No.')
                                                ->label('STF No.')
                                                ->placeholder('e.g., STF-2024-001')
                                                ->maxLength(255),
                                            TextInput::make('quantity')
                                                ->label('Quantity')
                                                ->placeholder('e.g., 10')
                                                ->numeric(),

                                            Textarea::make('parts_description')
                                                ->label('Parts Description')
                                                ->placeholder('parts description...')
                                                ->rows(2),
                                        ])
                                        ->columns(1)
                                        ->itemLabel(fn (array $state): ?string => $state['item_name'] ?? 'New Issuance of Materials Used'
                                        )
                                        ->addActionLabel('Add Issuance of Materials Used')
                                        ->defaultItems(3)
                                        ->collapsible()
                                        ->cloneable(),
                                    Repeater::make('return_of_materials')
                                        ->label('Return of Materials Used')
                                        ->defaultItems(2)
                                        ->schema([
                                            TextInput::make('STF No.')
                                                ->label('STF No.')
                                                ->placeholder('e.g., STF-2024-001')
                                                ->maxLength(255),
                                            TextInput::make('quantity')
                                                ->label('Quantity')
                                                ->placeholder('e.g., 10')
                                                ->numeric(),

                                            Textarea::make('parts_description')
                                                ->label('Parts Description')
                                                ->placeholder('parts description...')
                                                ->rows(2),
                                        ])
                                        ->columns(1)
                                        ->itemLabel(fn (array $state): ?string => $state['item_name'] ?? 'New Return of Materials Used'
                                        )
                                        ->addActionLabel('Add Return of Materials Used')
                                        ->defaultItems(3)
                                        ->collapsible()
                                        ->cloneable(),
                                ]),
                        ]),

                ])
                    ->columnSpanFull()
                    ->skippable(),
            ]);
    }
}
