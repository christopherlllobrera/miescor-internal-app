<?php

namespace App\Filament\Resources\Fleet\Dispatchings\Schemas;

use App\Filament\Forms\Components\OpenStreetMapField;
use App\Models\Dispatchings;
use App\Models\Driver;
use App\Models\Passenger;
use App\Models\RequestingOffice;
use App\Models\Vehicles;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class DispatchingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Location Details')
                        ->icon('heroicon-o-map')
                        ->description('Enter pickup and drop-off addresses')
                        ->schema([
                            Section::make('Location Details')
                                ->icon('heroicon-o-map-pin')
                                ->description('Set the pickup and drop-off points for your trip. Accurate locations help ensure smooth dispatching.')
                                ->schema([
                                    OpenStreetMapField::make('from_location')
                                        ->label('From')
                                        ->columnstart(1)
                                        ->required()
                                        ->searchable()
                                        ->placeholder('Enter or click on map to select origin location'),
                                    OpenStreetMapField::make('to_location')
                                        ->label('To')
                                        ->required()
                                        ->searchable()
                                        ->placeholder('Enter or click on map to select destination location'),
                                ])
                                ->columns(['default' => 1, 'sm' => 2, 'md' => 2, 'lg' => 2, 'xl' => 2, '2xl' => 2])
                                ->columnSpan(['default' => 1, 'sm' => 2, 'md' => 2, 'lg' => 2, 'xl' => 2, '2xl' => 2]),
                        ]),
                    Step::make('Trip Details')
                        ->icon('heroicon-o-map-pin')
                        ->description('Select preferred departure time.')
                        ->schema([
                            Section::make('Trip Details')
                                ->icon('heroicon-o-map-pin')
                                ->description('Set the trip time, choose its priority level, and update the current status to keep everything organized and on track.')
                                ->schema([
                                    DateTimePicker::make('departure_time')
                                        ->label('Departure Time')->required()
                                        ->validationMessages([
                                            'required' => 'Departure time field is required.',
                                        ]),
                                    ToggleButtons::make('status')
                                        ->options([
                                            Dispatchings::STATUS_UNASSIGNED => 'Unassigned',
                                            Dispatchings::STATUS_ASSIGNED => 'Assigned',
                                            Dispatchings::STATUS_REQUESTED => 'Requested',
                                            Dispatchings::STATUS_UNSERVED => 'Unserved',
                                            Dispatchings::STATUS_BUMP_OFF => 'Bump-off',
                                            Dispatchings::STATUS_CANCELLED => 'Cancelled',
                                            Dispatchings::STATUS_COMPLETED => 'Completed',
                                        ])
                                        ->colors([
                                            Dispatchings::STATUS_UNASSIGNED => 'primary',
                                            Dispatchings::STATUS_ASSIGNED => 'success',
                                            Dispatchings::STATUS_REQUESTED => 'info',
                                            Dispatchings::STATUS_UNSERVED => 'danger',
                                            Dispatchings::STATUS_BUMP_OFF => 'warning',
                                            Dispatchings::STATUS_CANCELLED => 'danger',
                                            Dispatchings::STATUS_COMPLETED => 'success',
                                        ])
                                        ->inline()->required()->live()
                                        ->validationMessages([
                                            'required' => 'Status field is required.',
                                        ])
                                        ->default(Dispatchings::STATUS_ASSIGNED),
                                    Radio::make('priority_level')
                                        ->label('Priority Level')->required()
                                        ->options([
                                            'High' => 'High',
                                            'Medium' => 'Medium',
                                            'Low' => 'Low',
                                        ])->default('Medium')
                                        ->inline()
                                        ->validationMessages([
                                            'required' => 'Priority level field is required.',
                                        ]),
                                ])->columns(2),
                            Section::make('Trip Timestamps')
                                ->columns(['default' => 1, 'sm' => 2, 'md' => 2, 'lg' => 2, 'xl' => 2, '2xl' => 2])
                                ->columnSpan(['default' => 1, 'sm' => 2, 'md' => 2, 'lg' => 2, 'xl' => 2, '2xl' => 2])
                                ->hiddenOn('create')
                                ->schema([
                                    DateTimePicker::make('en_route_time')
                                        ->label('En Route Time')

                                        ->placeholder('Set when trip starts'),
                                    DateTimePicker::make('complete_time')
                                        ->label('Complete Time')
                                        ->placeholder('Set when trip completes')
                                        ->live()
                                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                            $enRouteTime = $get('en_route_time');
                                            if ($enRouteTime && $state) {
                                                $start = Carbon::parse($enRouteTime);
                                                $end = Carbon::parse($state);
                                                $hours = $start->diffInHours($end);
                                                $minutes = $start->diffInMinutes($end) % 60;

                                                // Format as human readable: "2 hours", "1 hour 30 minutes", etc.
                                                $parts = [];
                                                if ($hours > 0) {
                                                    $parts[] = $hours.' '.($hours === 1 ? 'hour' : 'hours');
                                                }
                                                if ($minutes > 0) {
                                                    $parts[] = $minutes.' '.($minutes === 1 ? 'minute' : 'minutes');
                                                }
                                                $renderedHour = ! empty($parts) ? implode(' ', $parts) : '0 minutes';

                                                $set('rendered_hour', $renderedHour);
                                            }

                                            // Calculate Out of Service Hours (if complete time is beyond driver's shift_out)
                                            $driverId = $get('driver_id');
                                            if ($driverId && $state) {
                                                $driver = Driver::find($driverId);
                                                if ($driver && $driver->shift_out) {
                                                    $completeTime = Carbon::parse($state);
                                                    // Combine shift_out time with the date from complete_time
                                                    $shiftOutTime = Carbon::parse($completeTime->format('Y-m-d').' '.$driver->shift_out);

                                                    if ($completeTime->greaterThan($shiftOutTime)) {
                                                        $oosHours = $shiftOutTime->diffInHours($completeTime);
                                                        $oosMinutes = $shiftOutTime->diffInMinutes($completeTime) % 60;

                                                        $oosParts = [];
                                                        if ($oosHours > 0) {
                                                            $oosParts[] = $oosHours.' '.($oosHours === 1 ? 'hour' : 'hours');
                                                        }
                                                        if ($oosMinutes > 0) {
                                                            $oosParts[] = $oosMinutes.' '.($oosMinutes === 1 ? 'minute' : 'minutes');
                                                        }
                                                        $outOfService = ! empty($oosParts) ? implode(' ', $oosParts) : null;
                                                        $set('out_of_service', $outOfService);
                                                    } else {
                                                        $set('out_of_service', null);
                                                    }
                                                }
                                            }
                                        }),
                                    DateTimePicker::make('cancel_time')
                                        ->label('Cancel Time')
                                        ->disabled(fn (Get $get) => $get('status') !== Dispatchings::STATUS_CANCELLED)
                                        ->visible(fn (Get $get) => $get('status') == Dispatchings::STATUS_CANCELLED)
                                        ->placeholder('Set when trip is cancelled')
                                        ->hint('Only applicable if status is "Cancelled"'),
                                    TextInput::make('rendered_hour')
                                        ->label('Rendered Hours')
                                        ->placeholder('Automatically calculated based on trip times')
                                        ->disabled()
                                        ->dehydrated(true),
                                ]),
                            Section::make('VEA Controller and Ticket Number')
                                ->description('Enter the request item and VEA ticket number')
                                ->icon('heroicon-o-ticket')
                                ->schema([
                                    TextInput::make('request_item')
                                        ->label('Request Item')
                                        ->placeholder('Enter the request item manually'),
                                    TextInput::make('vea_ticket_number')
                                        ->label('VEA Ticket Number')->placeholder('Enter the VEA number manually')
                                        ->required()
                                        ->unique(ignoreRecord: true, modifyRuleUsing: function ($rule, $record) {
                                            return $record ? $rule->ignore($record) : $rule;
                                        })
                                        ->validationMessages([
                                            'unique' => 'This VEA ticket number is already in use. Please enter a different VEA ticket number.',
                                            'required' => 'VEA ticket number field is required.',
                                        ]),
                                    Textarea::make('purpose')
                                        ->label('Purpose')
                                        ->placeholder('Enter the purpose of the trip (optional)')
                                        ->rows(3)
                                        ->columnSpanFull(),
                                    // TextInput::make('reason')
                                    //     ->label('Reason')
                                    //     ->placeholder('Enter the reason for the trip (optional)'),

                                    Select::make('requesting_office_id')
                                        ->label('Cost Center')
                                        ->placeholder('Select a cost center')
                                        ->columnStart(1)
                                        ->options(
                                            RequestingOffice::query()
                                                ->orderBy('cost_center')
                                                ->pluck('cost_center', 'id')
                                        )
                                        ->createOptionForm([
                                            TextInput::make('cost_center')
                                                ->label('Cost Center')
                                                ->required(),
                                            TextInput::make('requestor_office')
                                                ->label('Requesting Office')
                                                ->required(),
                                        ])
                                        ->createOptionUsing(function (array $data): int {
                                            $office = RequestingOffice::create([
                                                'cost_center' => $data['cost_center'],
                                                'requestor_office' => $data['requestor_office'],
                                            ]);

                                            return $office->id;
                                        })
                                        ->createOptionModalHeading('Create New Cost Center')
                                        ->searchable()
                                        ->preload()
                                        ->live()
                                        ->afterStateUpdated(function ($state, Set $set) {
                                            if ($state) {
                                                $office = RequestingOffice::find($state);
                                                $set('requestor_office_display', $office?->requestor_office);
                                            } else {
                                                $set('requestor_office_display', null);
                                            }
                                        }),
                                    TextInput::make('requestor_office_display')
                                        ->label('Requesting Office')
                                        ->placeholder('Auto-filled based on cost center')
                                        ->readOnly()
                                        ->dehydrated(false),
                                    TextInput::make('passenger_count')
                                        ->label('Passenger Count')->numeric()
                                        ->placeholder('Enter number of passengers (optional)')
                                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                            $count = (int) $state;

                                            if ($count <= 0) {
                                                $set('passengers', []);

                                                return;
                                            }

                                            // Get existing passengers
                                            $existingPassengers = $get('passengers') ?? [];
                                            $existingCount = count($existingPassengers);

                                            if ($count > $existingCount) {
                                                // Add more empty passenger entries
                                                for ($i = $existingCount; $i < $count; $i++) {
                                                    $existingPassengers[] = [
                                                        'passenger_name' => '',
                                                        'passenger_contact_no' => '',
                                                        'passenger_pick_up_location' => '',
                                                    ];
                                                }
                                            } elseif ($count < $existingCount) {
                                                // Trim excess passengers
                                                $existingPassengers = array_slice($existingPassengers, 0, $count);
                                            }

                                            $set('passengers', array_values($existingPassengers));
                                        })
                                        ->validationMessages([
                                            'numeric' => 'Passenger count must be a number.',
                                        ])
                                        ->live(),

                                ])
                                ->columns(['default' => 1, 'sm' => 2, 'md' => 2, 'lg' => 2, 'xl' => 2, '2xl' => 2])
                                ->columnSpan(['default' => 1, 'sm' => 2, 'md' => 2, 'lg' => 2, 'xl' => 2, '2xl' => 2]),
                        ]),
                    Step::make('Assignment')
                        ->icon('heroicon-o-identification')
                        ->description('Confirm assignment before proceeding.')
                        ->schema([
                            Section::make('Vehicle Information')
                                ->description('Vehicle Information')
                                ->icon('heroicon-o-truck')
                                ->columns(['default' => 1, 'sm' => 2, 'md' => 2, 'lg' => 2, 'xl' => 2, '2xl' => 2])
                                ->columnSpan(['default' => 1, 'sm' => 2, 'md' => 2, 'lg' => 2, 'xl' => 2, '2xl' => 2])
                                ->schema([
                                    Select::make('vehicle_id')
                                        ->label('Vehicle')
                                        ->relationship('vehicle', 'plate_number')
                                        ->searchable()->preload()->required()->live()
                                        ->afterStateUpdated(function ($state, Set $set) {
                                            if ($state) {
                                                $vehicle = Vehicles::find($state);
                                                if ($vehicle) {
                                                    $set('company_no', $vehicle->company_no ?: '');
                                                    $set('model', $vehicle->model ?: '');
                                                    $set('vehicle_category', $vehicle->vehicle_category ?: '');
                                                    $set('vehicle_type', $vehicle->vehicle_type ?: '');
                                                    $set('fuel_type', $vehicle->fuel_type ?: '');
                                                    $set('maker', $vehicle->maker ?: '');
                                                }
                                            }
                                        })
                                        ->afterStateHydrated(function ($state, Set $set) {
                                            if ($state) {
                                                $vehicle = Vehicles::find($state);
                                                if ($vehicle) {
                                                    $set('company_no', $vehicle->company_no ?: '');
                                                    $set('model', $vehicle->model ?: '');
                                                    $set('vehicle_category', $vehicle->vehicle_category ?: '');
                                                    $set('vehicle_type', $vehicle->vehicle_type ?: '');
                                                    $set('fuel_type', $vehicle->fuel_type ?: '');
                                                    $set('maker', $vehicle->maker ?: '');
                                                }
                                            }
                                        }),
                                    TextInput::make('company_no')
                                        ->label('Company No.')->readOnly()
                                        ->placeholder('Automatically filled based on selected vehicle')
                                        ->dehydrated(false),
                                    TextInput::make('model')
                                        ->label('Vehicle Model')->readOnly()
                                        ->placeholder('Automatically filled based on selected vehicle')
                                        ->dehydrated(false),
                                    TextInput::make('vehicle_category')
                                        ->label('Vehicle Category')->readOnly()
                                        ->placeholder('Automatically filled based on selected vehicle')
                                        ->dehydrated(false),
                                    TextInput::make('vehicle_type')
                                        ->label('Vehicle Type')->readOnly()
                                        ->placeholder('Automatically filled based on selected vehicle')
                                        ->dehydrated(false),
                                    TextInput::make('fuel_type')
                                        ->label('Fuel Type')->readOnly()
                                        ->placeholder('Automatically filled based on selected vehicle')
                                        ->dehydrated(false),
                                ]),
                            Section::make('Driver information')
                                ->icon('heroicon-o-user')
                                ->description('Assign driver and confirm trip details')
                                ->columns(['default' => 1, 'sm' => 2, 'md' => 2, 'lg' => 2, 'xl' => 2, '2xl' => 2])
                                ->columnSpan(['default' => 1, 'sm' => 2, 'md' => 2, 'lg' => 2, 'xl' => 2, '2xl' => 2])
                                ->schema([
                                    Select::make('driver_id')
                                        ->label('Operator / Driver')
                                        ->options(fn () => Driver::with('employee')->get()->mapWithKeys(fn ($driver) => [
                                            $driver->id => $driver->employee?->full_name ?? $driver->empNo,
                                        ]))
                                        ->searchable()->preload()->live()
                                        ->afterStateUpdated(function ($state, Set $set) {
                                            if ($state) {
                                                $driver = Driver::with('employee')->find($state);
                                                if ($driver) {
                                                    $set('personnel_number', $driver->empNo);
                                                    $set('contact_number', $driver->contactno);
                                                    $set('shift_in', $driver->shift_in);
                                                    $set('shift_out', $driver->shift_out);
                                                }
                                            } else {
                                                $set('personnel_number', null);
                                                $set('contact_number', null);
                                                $set('shift_in', null);
                                                $set('shift_out', null);
                                            }
                                        })
                                        ->afterStateHydrated(function ($state, Set $set) {
                                            if ($state) {
                                                $driver = Driver::with('employee')->find($state);
                                                if ($driver) {
                                                    $set('personnel_number', $driver->empNo);
                                                    $set('contact_number', $driver->contactno);
                                                    $set('shift_in', $driver->shift_in);
                                                    $set('shift_out', $driver->shift_out);
                                                }
                                            }
                                        }),
                                    TextInput::make('personnel_number')
                                        ->label('PERNR / Man No.')->disabled()
                                        ->placeholder('This field is automatically filled based on selected operator')
                                        ->dehydrated(false),
                                    Text::make('Adjust shift times for this trip if needed.')
                                        ->columnSpanFull(),
                                    TimePicker::make('shift_in')
                                        ->label('Driver Shift In')
                                        ->placeholder('Automatically filled based on selected operator')
                                        ->dehydrated(false),
                                    TimePicker::make('shift_out')
                                        ->label('Driver Shift Out')
                                        ->placeholder('Automatically filled based on selected operator')
                                        ->dehydrated(false),
                                    TextInput::make('out_of_service')
                                        ->label('Out of Service Hours')
                                        ->placeholder('Auto-calculated if trip ends after driver shift')
                                        ->hint('Calculated when complete time exceeds driver\'s shift out')
                                        ->disabled()
                                        ->hiddenOn('create')
                                        ->dehydrated(true),
                                    TextInput::make('contact_number')
                                        ->label('Contact Number')->prefix('+63')->disabled()
                                        ->inputMode('decimal')->maxLength(11)
                                        ->placeholder('Automatically filled based on selected operator')
                                        ->dehydrated(false),
                                ]),
                            Section::make('Odometer')
                                ->description('Enter odometer readings for the trip')
                                ->columns(['default' => 1, 'sm' => 2, 'md' => 2, 'lg' => 2, 'xl' => 2, '2xl' => 2])
                                ->schema([
                                    TextInput::make('odometer_out')
                                        ->label('Odometer Out')->numeric()
                                        ->placeholder('Enter odometer reading'),
                                    TextInput::make('odometer_in')
                                        ->label('Odometer In')->numeric()
                                        ->placeholder('Enter odometer reading')
                                        ->hiddenOn('create'),
                                ]),
                            Section::make('Passenger Information')
                                ->description('Enter passenger information')
                                ->icon('heroicon-o-identification')
                                ->columns(['default' => 1, 'sm' => 2, 'md' => 2, 'lg' => 2, 'xl' => 2, '2xl' => 2])
                                ->columnSpan(['default' => 1, 'sm' => 2, 'md' => 2, 'lg' => 2, 'xl' => 2, '2xl' => 2])
                                ->schema([
                                    Repeater::make('passengers')
                                        ->relationship('passengers')
                                        ->columns(['default' => 1, 'sm' => 2, 'md' => 2, 'lg' => 2, 'xl' => 2, '2xl' => 2])
                                        ->columnSpan(['default' => 1, 'sm' => 2, 'md' => 2, 'lg' => 2, 'xl' => 2, '2xl' => 2])
                                        ->schema([
                                            TextInput::make('passenger_name')
                                                ->label('Passenger Name')->maxLength(255),
                                            TextInput::make('passenger_contact_no')
                                                ->label('Contact Number')
                                                ->numeric()
                                                ->placeholder('09XXXXXXXXX')
                                                ->maxLength(11),
                                            OpenStreetMapField::make('passenger_pick_up_location')
                                                ->label('Pick-up Location')
                                                ->maxLength(255)->live()
                                                ->placeholder('Enter pick-up location (optional)'),
                                        ])
                                        ->itemLabel(fn (array $state): ?string => $state['passenger_name'] ?? 'Passenger')
                                        ->reorderable()->addable()->deletable()->addActionLabel('Add passenger')->reorderableWithDragAndDrop(false)->live(true),
                                ]),
                        ]),
                ])
                    ->skippable()
                    ->columnSpanFull()
                    ->submitAction(new HtmlString(Blade::render(
                        <<<'BLADE'
                            <x-filament::button
                                type="submit"
                                size="sm">
                                Submit
                            </x-filament::button>
                        BLADE))),
            ]);
    }
}
