<?php

namespace App\Filament\Clusters\WorkOrder\PreventiveWorkOrders\Schemas;

use App\Models\Employee;
use App\Models\PreventiveWorkOrder;
use App\Models\Vehicles;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PreventiveWorkOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Job Order')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Section::make('Job Order Details')
                                ->description('Enter the job order reference numbers and billing information')
                                ->icon('heroicon-o-clipboard-document-list')
                                ->collapsible()
                                ->schema([
                                    TextInput::make('job_order_no')
                                        ->label('Job Order No.')
                                        ->placeholder('e.g., PW-2025-00001') // PW-2025-XXXXX
                                        ->default(function () {
                                            $JobOrderNo = PreventiveWorkOrder::orderBy('job_order_no', 'desc')->first()?->job_order_no;
                                            if ($JobOrderNo) {
                                                $lastNumber = (int) Str::afterLast($JobOrderNo, '-');

                                                return 'PW-2025-'.str_pad(++$lastNumber, 6, '0', STR_PAD_LEFT);
                                            } else {
                                                return 'PW-2025-00000';
                                            }
                                        })
                                        ->unique(ignoreRecord: true)
                                        ->maxLength(255),
                                    Select::make('vehicle_id')
                                        ->options(Vehicles::all()->pluck('plate_number', 'id'))
                                        ->searchable()->preload()
                                        ->label('Vehicle No.'),
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
                                    Select::make('preventive_maintenance_type')
                                        ->options([
                                            'A' => 'A',
                                            'B' => 'B',
                                            'C' => 'C',
                                        ]),
                                    DateTimePicker::make('job_order_date')
                                        ->label('Job Order Date')
                                        ->default(now()),
                                    DatePicker::make('job_order_assigned_date')
                                        ->label('Assigned Date'),
                                    DatePicker::make('job_order_accomplished_date')
                                        ->label('Accomplished Date'),
                                    Select::make('supervisor_id')
                                        ->label('Supervisor')
                                        ->options(
                                            fn (): Collection => Employee::where('CompNo', 1103)
                                                ->whereNotNull('PostNo')
                                                ->get()
                                                ->mapWithKeys(fn ($employee) => [
                                                    $employee->EmpNo => $employee->full_name,
                                                ])
                                        )
                                        ->searchable()->preload(),
                                    Select::make('leadman_id')
                                        ->label('Leadman')
                                        ->options(
                                            fn (): Collection => Employee::where('CompNo', 1103)
                                                ->whereNotNull('PostNo')
                                                ->get()
                                                ->mapWithKeys(fn ($employee) => [
                                                    $employee->EmpNo => $employee->full_name,
                                                ])
                                        )
                                        ->searchable()->preload(),
                                ])->columns([
                                    'default' => 1,
                                    'sm' => 2,
                                    'md' => 2,
                                    'lg' => 3,
                                    'xl' => 3,
                                    '2xl' => 3,
                                ])->columnSpan([
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
                                        ->searchable()->preload()
                                        ->columnstart(1),
                                    TextInput::make('contact_person_email'
                                    )->label('Email')->email(),
                                    TextInput::make('contact_person_no')
                                        ->label('Contact No.')->prefix('+63')->numeric(),
                                    FileUpload::make('contracted_attachment')
                                        ->label('Contracted File Attachment')
                                        ->columnSpanFull()->directory('vehicle_job_orders'),
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
                        ]),
                    Step::make('PMS Checklist (Internal)')

                        ->schema([
                            Repeater::make('steering_item')
                                ->label('Steering')
                                ->schema([
                                    Radio::make('oil_level_and_water_level')
                                        ->label('1. Oil Level and Water Level')
                                        ->inline()
                                        ->inlineLabel()
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ]),
                                    Radio::make('oil_pressure')
                                        ->label('2. Oil Pressure')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('engine_transmission_mounting')
                                        ->label('3. Engine & Transmission Mounting')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('fuel_pump')
                                        ->label('4. Fuel Pump')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('fuel_tank_line')
                                        ->label('5. Fuel Tank/Line')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('bolts')
                                        ->label('6. Manifolds Nuts / Bolts')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('air_cleaner')
                                        ->label('7. Oil/Air Cleaner Elements/Housing')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),

                                    Radio::make('engine_operation_condition')
                                        ->label('8. Engine Operating Condition')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('fan_belt')
                                        ->label('9. Fan Belt')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('timming_belt')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel()
                                        ->label('10. Timming Belt'),
                                ])->addable(false)->deletable(false)->reorderable(false)->collapsible(),
                            Repeater::make('brake_item')
                                ->label('Brake')
                                ->schema([
                                    Radio::make('brake_pedal')
                                        ->label('1. Brake Pedal')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('master_cylinder')
                                        ->label('2. Master Cylinder')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('brake_cylinder')
                                        ->label('3. Brake Cylinder')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('brake_booster')
                                        ->label('4. Brake Booster')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('line_cooper')
                                        ->label('5. Line Cooper/Flexible Hose')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('air_compressor')
                                        ->label('6. Air Compressor')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('brake_chamber')
                                        ->label('7. Brake Chamber')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('hand_brake_condition')
                                        ->label('8. Hand Brake Condition')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('hand_brake_cable')
                                        ->label('9. Hand brake linkage cable and lever')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('disk_drum_brake')
                                        ->label('10. Disk/Drum Brake')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('brake_vacuum_pump')
                                        ->label('11. Brake Vacuum Pump')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('exhaust_brake_linkage')
                                        ->label('12. Exhaust Brake and Linkage')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                ])->addable(false)->deletable(false)->reorderable(false)->collapsible(),
                            Repeater::make('exhaust_item')
                                ->label('Exhaust')
                                ->schema([
                                    Radio::make('muffler_hanger_brackert')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel()
                                        ->label('1. Muffler Hanger Bracket'),
                                ])->addable(false)->deletable(false)->reorderable(false)->collapsible(),
                            Repeater::make('front_suspension_item')
                                ->label('Front Suspension')
                                ->schema([
                                    Radio::make('upper_link_assemble')
                                        ->label('1. Upper Link Assemble and Bushing')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('lower_link_assemble')
                                        ->label('2. Lower Link Assemble and Bushing')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('torsion_bar_spring')
                                        ->label('3. Torsion Bar Spring')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('anchor_arm_bolts')
                                        ->label('4. Anchor Arms and Bolts')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('schock_absorber')
                                        ->label('5. Shock Absorber')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('upper_lower_ball_joint')
                                        ->label('6. Upper and Lower Ball Joint')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('Stabilizer_bar and Bushing')
                                        ->label('7. Stabilizer Bar and Bushing')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('drive_shaft')
                                        ->label('8. Drive Shaft Assy')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('cv_joint')
                                        ->label('9. CV Joint, Boot and Boot Band')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),

                                ])->addable(false)->deletable(false)->reorderable(false)->collapsible(),
                            Repeater::make('rear_axle_item')
                                ->label('Rear Axle')
                                ->schema([
                                    Radio::make('rear_axle_shaft')
                                        ->label('1. Rear Axle Shaft and Wheel Bearing')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('differential_gear_oil')
                                        ->label('2. Differential Gear Oil')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('rear_spring')
                                        ->label('3. Rear Spring (Leaf Spring)')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('rear_spring_pin')
                                        ->label('4. Rear Spring Pin, Busing and Shackle')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('rear_spring_ubolt')
                                        ->label('5. Rear Spring U-Bolt & Center Post')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('shock_absorber')
                                        ->label('6. Shock Absorber')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                ])->addable(false)->deletable(false)->reorderable(false)->collapsible(),
                            Repeater::make('clutch_item')
                                ->label('Clutch')
                                ->schema([
                                    Radio::make('clutch_pedal')
                                        ->label('1. Clutch Pedal and Play')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('clutch_master_cylinder')
                                        ->label('2. Clutch Master Cylinder')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('clutch_output')
                                        ->label('3. Clutch Output')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('clutch_booster')
                                        ->label('4. clutch Booster')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('clutch_fluid')
                                        ->label('5. Clutch Fluid')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('clutch_line')
                                        ->label('6. Clutch Line (Steel & Flexible')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('clutch_master_assembly')
                                        ->label('7. Clutch Master Assembly')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('clutch_output_assembly')
                                        ->label('8. Clutch Output Assembly')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),

                                ])->addable(false)->deletable(false)->reorderable(false)->collapsible(),
                            Repeater::make('transmission_item')
                                ->label('Transmission')
                                ->schema([
                                    Radio::make('transmission_oil_level')
                                        ->label('1. Transmission Oil Level')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('control_lever_knob_and_boot')
                                        ->label('2. Control Lever, Knob And Boot')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('reverse_lamp_switch')
                                        ->label('3. Reverse Lamp Switch')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('transfer_case')
                                        ->label('4. Transfer Case')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('power_take_off_pto')
                                        ->label('5. Power Take Off PTO')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('pto_drive_shaft_and_universal_joint')
                                        ->label('6. PTO Drive Shaft & Universal Joint')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('hydraulic_pump')
                                        ->label('7. Hydraulic Pump')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                ])->addable(false)->deletable(false)->reorderable(false)->collapsible(),
                            Repeater::make('propeller_item')
                                ->label('Propeller')
                                ->schema([
                                    Radio::make('propeller_shaft_and_bolts')
                                        ->label('1. Propeller Shaft & Bolts')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('universal_joint')
                                        ->label('2. Universal Joint')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('center_bearing')
                                        ->label('3. Center Bearing')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('center_bearing_support_and_bracket')
                                        ->label('4. Center Bearing Support & Bracket')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('bell_crank')
                                        ->label('5. Bell Crank')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                ])->addable(false)->deletable(false)->reorderable(false)->collapsible(),
                        ]),
                    Step::make('PMS Checklist (External)')
                        ->schema([
                            Repeater::make('body_item')
                                ->label('Body')
                                ->schema([
                                    Radio::make('body_front_and_rear')
                                        ->label('1. Body Front & Rear')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('bumper_front_and_rear')
                                        ->label('2. Bumper Front & Rear')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('front_grille')
                                        ->label('3. Front Grille')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('hood_hood_lock_and_hinge')
                                        ->label('4. Hood, Hood Lock & Hinge')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('front_door_glass_hinge_lock_and_mechanism')
                                        ->label('5. Front Door, Glass, Hinge, Lock & Mechanism')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('rear_door_glass_hinge_lock_and_mechanism')
                                        ->label('6. Rear Door, Glass, Hinge, Lock & Mechanism')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('front_and_rear_seat_upholstery')
                                        ->label('7. Front & Rear Seat Upholstery')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('windshield_glass_rubber')
                                        ->label('8. Windshield Glass Rubber')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('back_window_glass_rubber')
                                        ->label('9. Back Window Glass Rubber')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('side_window_glass_rubber')
                                        ->label('10. Side Window Glass Rubber')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('aircon')
                                        ->label('11. Aircon')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('washing')
                                        ->label('12. Washing')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('roadtest')
                                        ->label('13. Roadtest')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('cooling_system')
                                        ->label('14. Cooling System')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('water_pump')
                                        ->label('15. Water Pump')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('radiator')
                                        ->label('16. Radiator')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('oil_cooler')
                                        ->label('17. Oil Cooler')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('hoses')
                                        ->label('18. Hoses')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('reservoir_tank')
                                        ->label('19. Reservoir Tank')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),

                                ])->addable(false)->deletable(false)->reorderable(false)->collapsible(),
                            Repeater::make('tire_item')
                                ->label('Tire')
                                ->schema([
                                    Radio::make('tire_condition')
                                        ->label('1. Tire Condition')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('check_bolts')
                                        ->label('2. Check Bolts')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('spare_tire')
                                        ->label('3. Spare Tire (as necessary)')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                ])->addable(false)->deletable(false)->reorderable(false)->collapsible(),
                            Repeater::make('electrical_item')
                                ->label('Electrical')
                                ->schema([
                                    Radio::make('wiring_ligths_indicator')
                                        ->label('1. Wiring, Lights & Indicators')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('odometer')
                                        ->label('2. Odometer')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('horn')
                                        ->label('3. Horn')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('charging_rate')
                                        ->label('4. Charging Rate')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('battery_water_level')
                                        ->label('5. Battery Water Level')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('battery_cable_and_terminal')
                                        ->label('6. Battery, Cable & Terminal & Bracket')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('wiper_blade_washer_condition')
                                        ->label('7. Wiper/Blade & Washer Condition')
                                        ->options([
                                            'OK' => 'OK',
                                            'N.A' => 'N.A',
                                            'Clean/Lube' => 'Clean/Lube',
                                            'Adjust/Align/Tighten' => 'Adjust/Align/Tighten',
                                            'Add' => 'Add',
                                            'Repair' => 'Repair',
                                            'Change' => 'Change',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                ])->addable(false)->deletable(false)->reorderable(false)->collapsible(),
                        ]),
                    Step::make('PMS Tagging')
                        ->schema([
                            Repeater::make('pms_tagging')
                                ->label('PMS Tagging')
                                ->schema([
                                    Radio::make('pms_tag')
                                        ->label('PMS Tag')
                                        ->options([
                                            'O.K., Correct Information' => 'O.K., Correct Information',
                                            'Wrong Information/Replace' => 'Wrong Information/Replace',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('user')
                                        ->label('User')
                                        ->options([
                                            'O.K., Correct Information' => 'O.K., Correct Information',
                                            'Wrong Information/Replace' => 'Wrong Information/Replace',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('plate_no')
                                        ->label('Plate No.')
                                        ->options([
                                            'O.K., Correct Information' => 'O.K., Correct Information',
                                            'Wrong Information/Replace' => 'Wrong Information/Replace',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('next_pms_schedule')
                                        ->label('Next PMS Schedule')
                                        ->options([
                                            'O.K., Correct Information' => 'O.K., Correct Information',
                                            'Wrong Information/Replace' => 'Wrong Information/Replace',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('date_of_pms')
                                        ->label('Date of PMS')
                                        ->options([
                                            'O.K., Correct Information' => 'O.K., Correct Information',
                                            'Wrong Information/Replace' => 'Wrong Information/Replace',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                    Radio::make('odometer_reading')
                                        ->label('Odometer Reading')
                                        ->options([
                                            'O.K., Correct Information' => 'O.K., Correct Information',
                                            'Wrong Information/Replace' => 'Wrong Information/Replace',
                                        ])
                                        ->inline()
                                        ->inlineLabel(),
                                ])->addable(false)->deletable(false)->reorderable(false)->collapsible(),
                        ]),

                ])->columnSpanFull()
                    ->skippable(),

            ]);
    }
}
