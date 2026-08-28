<?php

namespace App\Filament\Resources\AttendanceAuthorizationForms\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class AttendanceAuthorizationFormForm
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
                        Select::make('reason')
                            ->label('Reason')
                            ->options([
                                'TCD Malfunction' => 'TCD Malfunction',
                                'Forgot to Log in or Log out' => 'Forgot to Log in or Log out',
                                'Out of Base for Official Business' => 'Out of Base for Official Business',
                                'No Company ID' => 'No Company ID',
                            ])
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),
                Section::make('Attendance Authorization Correction')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('items')
                            ->columns(5)
                            ->label('List of AAF')
                            ->relationship('items')
                            ->schema([
                                DatePicker::make('date')
                                    ->label('Date')
                                    ->required(),
                                TimePicker::make('time_in')
                                    ->label('Time In')
                                    ->required(),
                                TimePicker::make('request_time_in')
                                    ->label('Request Time In')
                                    ->required()
                                    ->default('08:00:00'),
                                TimePicker::make('time_out')
                                    ->label('Time Out')
                                    ->required(),
                                TimePicker::make('request_time_out')
                                    ->label('Request Time Out')
                                    ->default('17:00:00')
                                    ->required(),
                            ])
                            ->minItems(1)
                            ->addActionLabel('Add AAF')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
