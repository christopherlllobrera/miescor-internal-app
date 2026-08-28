<?php

namespace App\Filament\Resources\OvertimeRequests\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class OvertimeRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Requestor Information')
                    ->columns(4)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('Name')
                            ->afterStateHydrated(fn ($component) => $component->state(Auth::user()?->name))
                            ->disabled(),
                        TextInput::make('empNo')
                            ->label('Employee No')
                            ->dehydrated()
                            ->default(fn () => Auth::user()?->EmpNo ?? Auth::user()?->empNo)
                            ->disabled(),
                        TextInput::make('location_id')
                            ->label('Sub Area')
                            ->dehydrated()
                            ->default(fn () => Auth::user()?->employee?->location?->LocDesc ?? Auth::user()?->employee?->LocNo)
                            ->disabled(),
                        Select::make('employee_group')
                            ->label('Employee Group')
                            ->options([
                                'Regular' => 'Regular',
                                'Probationary' => 'Probationary',
                                'Project Hire' => 'Project Hire',
                                'Fixed Term' => 'Fixed Term',
                                'Regular Work Pool' => 'Regular Work Pool',
                                'Service Agreement' => 'Service Agreement',
                                'Meralco Seconded' => 'Meralco Seconded',
                            ]),
                        Select::make('immediate_supervisor_id')
                            ->label('Immediate Supervisor (Approver)')
                            ->relationship('immediate_supervisor', 'EmpLName')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->EmpLName}, {$record->EmpFName}")
                            ->searchable(['EmpLName', 'EmpFName'])
                            ->preload()
                            ->required(),
                        Select::make('next_level_supervisor_id')
                            ->label('Next Level Supervisor')
                            ->relationship('next_level_supervisor', 'EmpLName')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->EmpLName}, {$record->EmpFName}")
                            ->searchable(['EmpLName', 'EmpFName'])
                            ->preload()
                            ->nullable(),
                    ]),
                Section::make('Overtime Request Details')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('items')
                            ->columns(5)
                            ->label('List of Overtime Requests')
                            ->relationship('items')
                            ->schema([
                                DatePicker::make('date')
                                    ->label('Date')
                                    ->required(),
                                TimePicker::make('ot_start')
                                    ->label('OT Start')
                                    ->required(),
                                TimePicker::make('ot_end')
                                    ->label('OT End')
                                    ->required(),
                                TextInput::make('number_of_hours')
                                    ->label('No. of Hours')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('reason')
                                    ->label('Reason')
                                    ->required(),
                            ])
                            ->minItems(1)
                            ->addActionLabel('Add OT Request')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
