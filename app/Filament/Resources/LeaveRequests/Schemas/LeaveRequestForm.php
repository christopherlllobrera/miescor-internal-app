<?php

namespace App\Filament\Resources\LeaveRequests\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Text;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;

class LeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(4)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('Name')
                            ->default(fn () => Auth::user()?->name)
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
                            ])
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('type', null)),
                        Select::make('schedule')
                            ->options([
                                '6AM - 3PM' => '6AM - 3PM',
                                '7AM - 4PM' => '7AM - 4PM',
                                '8AM - 5PM' => '8AM - 5PM',
                            ]),
                        TextInput::make('available_credits')
                            ->label('Available Credits'),
                        TextInput::make('vl_balance')
                            ->label('Vacation Leave Balance'),
                        TextInput::make('sl_balance')
                            ->label('Sick Leave Balance'),
                        Select::make('type')
                            ->label('Leave Type')
                            ->options(fn (Get $get): array => self::getLeaveTypesByGroup($get('employee_group')))
                            ->searchable()
                            ->required(),
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
                            ->columnSpan(3)
                            ->inline()
                            ->required(),
                        DatePicker::make('date_start'),
                        DatePicker::make('date_end'),
                        TextInput::make('days_total'),

                        Textarea::make('reason')
                            ->columnSpan(2),
                        FileUpload::make('attachment')
                            ->columnSpan(2),
                        Section::make('Approver')
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
                            ->preload()
                            ->searchable(),
                        Select::make('next_level_supervisor_id')
                            ->relationship('next_level_supervisor', 'EmpNo')
                            ->preload()
                            ->searchable(),
                        
                    ]),
            ]);
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
