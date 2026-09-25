<?php

namespace App\Filament\Resources\PayrollSelfService\LeaveRequests\Schemas;

use App\Models\BusinessUnits;
use App\Models\EmployeeStatus;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class LeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Leave Request Details')
                    ->columns(2)
                    ->columnSpan(2)
                    ->collapsible()
                    ->schema([
                        TextInput::make('vl_balance')
                            ->label('Vacation Leave Balance')
                            ->visible(fn (Get $get): bool => $get('type') !== 'Sick Leave'),
                        TextInput::make('sl_balance')
                            ->label('Sick Leave Balance')
                            ->visible(fn (Get $get): bool => $get('type') !== 'Vacation Leave'),
                        Select::make('type')
                            ->label('Leave Type')
                            ->options(fn (Get $get): array => self::getFilteredLeaveTypes($get('employee_group'), $get('type')))
                            ->searchable()
                            ->required()
                            ->live(),
                        DatePicker::make('date_start')
                            ->label('Date Start')
                            ->live(),
                        DatePicker::make('date_end')
                            ->label('Date End')
                            ->afterOrEqual('date_start')
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                $start = $get('date_start');
                                $end = $state;
                                if ($start && $end) {
                                    $days = Carbon::parse($start)->diffInDays(Carbon::parse($end)) + 1;
                                    $set('days_total', $days);
                                }
                            }),
                        TextInput::make('days_total')
                            ->label('Total')
                            ->disabled(),

                        ToggleButtons::make('duration')
                            ->options([
                                'full day' => 'Full Day',
                                'half day AM' => 'Half Day AM',
                                'half day PM' => 'Half Day PM',
                            ])
                            ->colors([
                                'full day' => 'info',
                                'half day AM' => 'info',
                                'half day PM' => 'info',

                            ])
                            ->inline()
                            ->required(),
                        Textarea::make('reason')
                            ->columnSpanFull()
                            ->rows(3),
                        FileUpload::make('attachment')
                            ->columnSpanFull(),
                        Section::make('Approver')
                            ->hidden()
                            ->icon('heroicon-o-exclamation-circle')
                            ->schema([
                                Placeholder::make('Guidelines')
                                    // ->label('')
                                    ->content(new HtmlString('
                                            <ul class="list-disc list-inside space-y-1 text-sm text-gray-600 dark:text-gray-400">
                                                <li><strong>Regular:</strong> Physician</li>
                                                <li><strong>Non-regular:</strong> Physician and IS</li>
                                                <li><strong>Project Site:</strong> Nurse and IS (RWP)</li>
                                            </ul>
                                        ')),
                            ]),

                        Select::make('immediate_supervisor_id')
                            ->relationship('immediate_supervisor', 'EmpNo')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->EmpLName}, {$record->EmpFName}")
                            ->searchable(['EmpLName', 'EmpFName'])
                            ->preload()
                            ->hidden(),
                        Select::make('next_level_supervisor_id')
                            ->relationship('next_level_supervisor', 'EmpNo')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->EmpLName}, {$record->EmpFName}")
                            ->searchable(['EmpLName', 'EmpFName'])
                            ->preload()
                            ->hidden(),
                    ]),
                Section::make()
                    ->schema([
                        TextInput::make('Name')
                            ->default(fn () => Auth::user()?->name)
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
                            ->preload(),
                        Select::make('business_unit')
                            ->label('Business Unit')
                            ->options(fn () => BusinessUnits::pluck('BusinessUnitDesc', 'BusinessUnitDesc')->toArray())
                            ->default(fn () => LeaveRequest::getDefaultBusinessUnit())
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('org_unit', null)),
                        Select::make('org_unit')
                            ->label('Sub Area')
                            ->options(fn (Get $get, ?string $state) => LeaveRequest::getSubAreaOptions($get('business_unit'), $state))
                            ->default(fn () => LeaveRequest::getDefaultSubArea())
                            ->searchable()
                            ->preload(),

                        Select::make('employee_group')
                            ->label('Employee Group')
                            ->options(fn () => EmployeeStatus::options())
                            ->default(fn () => LeaveRequest::getDefaultEmployeeGroup())
                            ->preload()
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('type', null)),
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

    /** @var array<int, string> */
    public const array SICK_LEAVE_TYPES = [
        'Sick Leave',
        'Maternity Leave',
        'Magna Carta Leave',
    ];

    /** @var array<int, string> */
    public const array VACATION_LEAVE_TYPES = [
        'Vacation Leave',
        'Paternity Leave',
        'Birthday Leave',
        'Solo Parent Leave',
        'Bereavement Leave',
        'Leave without Pay',
        'Union Leave',
        'VAWC Leave',
        'Service Incentive Leave',
    ];

    /**
     * @return array<string, string>
     */
    public static function getFilteredLeaveTypes(?string $group, ?string $selectedType): array
    {
        $types = self::getLeaveTypesByGroup($group);

        if (in_array($selectedType, self::SICK_LEAVE_TYPES)) {
            $types = array_intersect_key($types, array_flip(self::SICK_LEAVE_TYPES));
        } elseif (in_array($selectedType, self::VACATION_LEAVE_TYPES)) {
            $types = array_intersect_key($types, array_flip(self::VACATION_LEAVE_TYPES));
        }

        return $types;
    }

    /**
     * @return array<string, string>
     */
    public static function getLeaveTypesByGroup(?string $group): array
    {
        $types = match ($group) {
            'Regular' => [
                'Sick Leave',
                'Maternity Leave',
                'Magna Carta Leave',
                'Leave without Pay',
                'Union Leave',
                'Bereavement Leave',
                'Solo Parent Leave',
                'Birthday Leave',
                'Paternity Leave',
                'Vacation Leave',
                'VAWC Leave',
            ],
            'Probationary' => [
                'Sick Leave',
                'Maternity Leave',
                'Magna Carta Leave',
                'Paternity Leave',
                'Solo Parent Leave',
                'VAWC Leave',
                'Leave without Pay',
            ],
            'Project Hire' => [
                'Sick Leave',
                'Maternity Leave',
                'Magna Carta Leave',
                'Paternity Leave',
                'Leave without Pay',
                'VAWC Leave',
                'Service Incentive Leave',
                'Solo Parent Leave',
            ],
            'Fixed Term' => [
                'Sick Leave',
                'Maternity Leave',
                'Magna Carta Leave',
                'VAWC Leave',
                'Leave without Pay',
                'Service Incentive Leave',
                'Solo Parent Leave',
                'Paternity Leave',
            ],
            'Regular Work Pool' => [
                'Sick Leave',
                'Maternity Leave',
                'Magna Carta Leave',
                'VAWC Leave',
                'Leave without Pay',
                'Bereavement Leave',
                'Solo Parent Leave',
                'Birthday Leave',
                'Paternity Leave',
                'Vacation Leave',
            ],
            'Service Agreement' => [
                'Sick Leave',
                'Leave without Pay',
                'Vacation Leave',
            ],
            'Meralco Seconded' => [
                'Sick Leave',
                'Leave without Pay',
                'Service Incentive Leave',
                'Bereavement Leave',
                'Solo Parent Leave',
                'Birthday Leave',
                'Paternity Leave',
                'Vacation Leave',
            ],
            default => [
                'Sick Leave',
                'Vacation Leave',
                'Leave without Pay',
                'Maternity Leave',
                'Paternity Leave',
                'Magna Carta Leave',
                'Solo Parent Leave',
                'VAWC Leave',
                'Bereavement Leave',
                'Birthday Leave',
                'Union Leave',
                'Service Incentive Leave',
            ],
        };

        return array_combine($types, $types);
    }
}
