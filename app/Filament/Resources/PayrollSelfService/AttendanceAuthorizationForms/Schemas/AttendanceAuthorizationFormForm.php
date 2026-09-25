<?php

namespace App\Filament\Resources\PayrollSelfService\AttendanceAuthorizationForms\Schemas;

use App\Models\AttendanceAuth;
use App\Models\BusinessUnits;
use App\Models\EmployeeStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class AttendanceAuthorizationFormForm
{
    public static function getDefaultRequestTimeIn(?string $schedule): string
    {
        return match ($schedule) {
            '6AM - 3PM' => '06:00:00',
            '7AM - 4PM' => '07:00:00',
            default => '08:00:00',
        };
    }

    public static function getDefaultRequestTimeOut(?string $schedule): string
    {
        return match ($schedule) {
            '6AM - 3PM' => '15:00:00',
            '7AM - 4PM' => '16:00:00',
            default => '17:00:00',
        };
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Attendance Authorization Correction')
                    ->columnSpan(2)
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        Repeater::make('items')
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 2,
                                'lg' => 3,
                                '2xl' => 2,
                            ])
                            ->columns([
                                'sm' => 1,
                                'md' => 2,
                            ])
                            ->label('Correction Entries')
                            ->relationship('items')
                            ->schema([
                                DatePicker::make('date')
                                    ->label('Date')
                                    ->required()
                                    ->distinct()
                                    ->validationMessages([
                                        'distinct' => 'This date has already been added in another entry.',
                                    ]),
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
                                TimePicker::make('time_in')
                                    ->label('Time In')
                                    ->required()
                                    ->columnStart(1),
                                TimePicker::make('request_time_in')
                                    ->label('Request Time In')
                                    ->required()
                                    ->default(fn (Get $get): string => self::getDefaultRequestTimeIn($get('../../schedule'))),
                                TimePicker::make('time_out')
                                    ->label('Time Out')
                                    ->required(),
                                TimePicker::make('request_time_out')
                                    ->label('Request Time Out')
                                    ->required()
                                    ->default(fn (Get $get): string => self::getDefaultRequestTimeOut($get('../../schedule'))),
                            ])
                            ->minItems(1)
                            ->addActionLabel('Add AAF'),
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
                        Select::make('schedule')
                            ->options([
                                '6AM - 3PM' => '6AM - 3PM',
                                '7AM - 4PM' => '7AM - 4PM',
                                '8AM - 5PM' => '8AM - 5PM',
                            ])
                            ->default('8AM - 5PM')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (?string $state, Set $set, Get $get): void {
                                $items = $get('items') ?? [];
                                if (! is_array($items) || empty($items)) {
                                    return;
                                }

                                $timeIn = self::getDefaultRequestTimeIn($state);
                                $timeOut = self::getDefaultRequestTimeOut($state);

                                foreach (array_keys($items) as $key) {
                                    $set("items.{$key}.request_time_in", $timeIn);
                                    $set("items.{$key}.request_time_out", $timeOut);
                                }
                            }),
                        Select::make('business_unit')
                            ->label('Business Unit')
                            ->options(fn () => BusinessUnits::pluck('BusinessUnitDesc', 'BusinessUnitDesc')->toArray())
                            ->default(fn () => AttendanceAuth::getDefaultBusinessUnit())
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('sub_area', null)),
                        Select::make('sub_area')
                            ->label('Sub Area')
                            ->options(fn (Get $get, ?string $state) => AttendanceAuth::getSubAreaOptions($get('business_unit'), $state))
                            ->default(fn () => AttendanceAuth::getDefaultSubArea())
                            ->searchable()
                            ->preload(),
                        Select::make('employee_group')
                            ->label('Employee Group')
                            ->options(fn () => EmployeeStatus::options())
                            ->default(fn () => AttendanceAuth::getDefaultEmployeeGroup())
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
