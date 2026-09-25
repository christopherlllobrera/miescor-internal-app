<?php

namespace App\Filament\Resources\PayrollSelfService\OvertimeRequests\Schemas;

use App\Models\BusinessUnits;
use App\Models\EmployeeStatus;
use App\Models\OvertimeRequest;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class OvertimeRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(4)
            ->components([
                Section::make('Overtime Request Details')
                    ->columnSpan(3)
                    ->collapsible()
                    ->schema([
                        Repeater::make('items')
                            ->columns(4)
                            ->label('List of Overtime Requests')
                            ->relationship('items')
                            ->schema([
                                DatePicker::make('date')
                                    ->label('Date')
                                    ->required(),
                                TimePicker::make('ot_start')
                                    ->label('OT Start')
                                    ->required()
                                    ->seconds(false)
                                    ->minutesStep(30)
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $end = $get('ot_end');
                                        if ($state && $end) {
                                            $start = Carbon::parse($state);
                                            $end = Carbon::parse($end);
                                            if ($end->lessThan($start)) {
                                                $end->addDay();
                                            }
                                            $set('number_of_hours', floor(abs($end->diffInMinutes($start)) / 30) * 0.5);
                                        }
                                    })
                                    ->live(),
                                TimePicker::make('ot_end')
                                    ->label('OT End')
                                    ->required()
                                    ->seconds(false)
                                    ->minutesStep(30)
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $start = Carbon::parse($get('ot_start'));
                                        $end = Carbon::parse($state);
                                        if ($end->lessThan($start)) {
                                            $end->addDay();
                                        }
                                        $totalHours = floor(abs($end->diffInMinutes($start)) / 30) * 0.5;
                                        $set('number_of_hours', $totalHours);
                                    })
                                    ->live(),
                                TextInput::make('number_of_hours')
                                    ->label('No. of Hours')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),
                                Textarea::make('reason')
                                    ->label('Reason')
                                    ->required()
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ])
                            ->minItems(5)
                            ->addActionLabel('Add OT Request')
                            ->columnSpanFull(),
                    ]),
                Section::make('Requestor Information')
                    ->collapsible()
                    ->schema([
                        TextInput::make('Name')
                            ->afterStateHydrated(fn ($component) => $component->state(Auth::user()?->name))
                            ->disabled(),
                        TextInput::make('empNo')
                            ->label('Employee No')
                            ->dehydrated()
                            ->default(fn () => Auth::user()?->EmpNo ?? Auth::user()?->empNo)
                            ->disabled(),
                        Select::make('business_unit')
                            ->label('Business Unit')
                            ->options(fn () => BusinessUnits::pluck('BusinessUnitDesc', 'BusinessUnitDesc')->toArray())
                            ->default(fn () => OvertimeRequest::getDefaultBusinessUnit())
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('location_id', null)),
                        Select::make('location_id')
                            ->label('Sub Area')
                            ->options(fn (Get $get, ?string $state) => OvertimeRequest::getSubAreaOptions($get('business_unit'), $state))
                            ->default(fn () => OvertimeRequest::getDefaultSubArea())
                            ->searchable()
                            ->preload(),
                        Select::make('employee_group')
                            ->label('Employee Group')
                            ->options(fn () => EmployeeStatus::options())
                            ->default(fn () => OvertimeRequest::getDefaultEmployeeGroup())
                            ->preload()
                            ->searchable(),
                        Select::make('immediate_supervisor_id')
                            ->label('Immediate Supervisor (Approver)')
                            ->relationship('immediate_supervisor', 'EmpLName')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->EmpLName}, {$record->EmpFName}")
                            ->searchable(['EmpLName', 'EmpFName'])
                            ->preload()
                            ->required()
                            ->hidden(),
                        Select::make('next_level_supervisor_id')
                            ->label('Next Level Supervisor')
                            ->relationship('next_level_supervisor', 'EmpLName')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->EmpLName}, {$record->EmpFName}")
                            ->searchable(['EmpLName', 'EmpFName'])
                            ->preload()
                            ->nullable()
                            ->hidden(),
                    ]),
            ]);
    }
}
