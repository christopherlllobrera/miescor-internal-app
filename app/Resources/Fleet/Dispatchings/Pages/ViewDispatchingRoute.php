<?php

namespace App\Filament\Resources\Fleet\Dispatchings\Pages;

// Update imports for v4
use App\Filament\Resources\Fleet\Dispatchings\Actions\CompleteTrip;
use App\Filament\Resources\Fleet\Dispatchings\Actions\ReportIncident;
use App\Filament\Resources\Fleet\Dispatchings\DispatchingResource;
use App\Models\Dispatchings;
use App\Models\Fuel;
use App\Models\Odometer;
use App\Models\Toll;
use App\Models\TollFare;
use App\Models\Vehicles;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;

class ViewDispatchingRoute extends Page
{
    protected static string $resource = DispatchingResource::class;

    protected string $view = 'filament.resources.dispatching-resource.pages.view-dispatching-route';

    public $record;

    public $fromCoordinates;

    public $toCoordinates;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);

        // Eager load the vehicle relationship
        if ($this->record) {
            $this->record->load('vehicle');
        }

        // Get the coordinates for map display only
        $this->geocodeLocations();

        // We no longer automatically calculate tolls based on route
        // The following code is commented out as we're now using manual toll point entry
        /*
        if ($this->record && $this->fromCoordinates && $this->toCoordinates &&
            !$this->record->toll_cost && !$this->record->toll_points) {
            $this->calculateTollsAndFuel();
        }
        */
    }

    protected function resolveRecord(int|string $key): Dispatchings
    {
        $model = static::getResource()::getModel();

        $record = $model::find($key);

        if ($record === null) {
            abort(404);
        }

        return $record;
    }

    protected function geocodeLocations(): void
    {
        // First check if coordinates are embedded in the location strings
        if ($this->record->from_location_coordinates) {
            $this->fromCoordinates = $this->record->from_location_coordinates;
        }

        if ($this->record->to_location_coordinates) {
            $this->toCoordinates = $this->record->to_location_coordinates;
        }

        // If we already have both coordinates, return early
        if ($this->fromCoordinates && $this->toCoordinates) {
            return;
        }

        // Known locations in the Philippines - organized as a static property
        $this->tryKnownLocations();
    }

    /**
     * Try to find coordinates from known locations in the Philippines
     */
    protected function tryKnownLocations(): void
    {
        $knownLocations = [
            'cainta' => [14.5764, 121.1182],
            'rizal' => [14.6037, 121.3084],
            'taytay' => [14.5691, 121.1333],
            'calabarzon' => [14.1008, 121.0794],
            'manila' => [14.5995, 120.9842],
            'quezon city' => [14.6760, 121.0437],
            'makati' => [14.5547, 121.0244],
            'pasig' => [14.5764, 121.0851],
            'taguig' => [14.5176, 121.0509],
            'pasay' => [14.5378, 121.0014],
            'paranaque' => [14.4793, 121.0198],
            'marikina' => [14.6507, 121.1029],
            'mandaluyong' => [14.5794, 121.0359],
            'san juan' => [14.6019, 121.0355],
            'caloocan' => [14.6499, 120.9809],
            'malabon' => [14.6681, 120.9658],
            'navotas' => [14.6696, 120.9399],
            'valenzuela' => [14.7011, 120.9830],
            'pateros' => [14.5456, 121.0681],
            'las pinas' => [14.4504, 120.9823],
            'muntinlupa' => [14.4081, 121.0415],
        ];

        // Check if the from location contains any known location
        if (! $this->fromCoordinates) {
            $fromLocation = strtolower($this->record->from_location);
            foreach ($knownLocations as $location => $coordinates) {
                if (strpos($fromLocation, $location) !== false) {
                    $this->fromCoordinates = $coordinates;
                    break;
                }
            }
        }

        // Check if the to location contains any known location
        if (! $this->toCoordinates) {
            $toLocation = strtolower($this->record->to_location);
            foreach ($knownLocations as $location => $coordinates) {
                if (strpos($toLocation, $location) !== false) {
                    $this->toCoordinates = $coordinates;
                    break;
                }
            }
        }
    }

    public function calculateTollsAndFuel(): void
    {
        if (! $this->record || ! $this->fromCoordinates || ! $this->toCoordinates) {
            $this->notifyDanger('Missing coordinates for toll calculation');

            return;
        }

        // Ensure we save coordinates to the record before calculation
        if ($this->fromCoordinates && $this->toCoordinates) {
            // Store coordinates in the record if they aren't already present
            if (! $this->record->from_location_coordinates) {
                $this->record->from_location = $this->record->from_location_display.' ['.implode(',', $this->fromCoordinates).']';
            }

            if (! $this->record->to_location_coordinates) {
                $this->record->to_location = $this->record->to_location_display.' ['.implode(',', $this->toCoordinates).']';
            }

            // Save the record with coordinates
            $this->record->save();
        }

        $this->record->calculateTollAndFuel();
        $this->record->refresh();
    }

    public function getTitle(): string|Htmlable
    {
        return new HtmlString('Route Map');
    }

    protected function getHeaderActions(): array
    {
        return [
            ReportIncident::make(),
            Action::make('en_route')
                ->label('En Route')
                ->visible(fn () => $this->record->canStartTrip())
                ->action(function () {
                    $this->record->startTrip();
                    $this->notifyInfo('Vehicle is now en route to the destination');
                    $this->record->update([
                        'en_route_time' => Carbon::now()->toDateTimeString(),
                    ]);
                    $this->redirectToRoute();
                }),
            CompleteTrip::make()
                ->visible(fn () => $this->record->canBeCompleted())
                ->hidden(fn () => $this->record['status'] !== 'En Route'),
            Action::make('cancel')
                ->label('Cancel Trip')
                ->visible(fn () => $this->record->canBeCancelled())
                ->requiresConfirmation()
                ->modalHeading('Cancel Trip')
                ->modalDescription('Please provide a reason.')
                ->modalSubmitActionLabel('Yes, cancel trip')
                ->form([
                    Textarea::make('cancellation_reason')
                        ->label('Reason for change')
                        ->required()
                        ->rows(2),
                ])
                ->action(function (array $data) {
                    $this->record->cancelTrip($data['cancellation_reason']);
                    $this->notifyDanger('Trip cancelled');
                    $this->record->update([
                        'cancel_time' => Carbon::now()->toDateTimeString(),
                    ]);
                    $this->redirectToRoute();
                })
                ->color('danger'),
        ];
    }

    /**
     * Calculate toll fare based on entry/exit points and vehicle class
     */
    public function recalculateFare($set, $get): void
    {
        $tollRoadId = $get('toll_road_id');
        $entryPointId = $get('entry_point_id');
        $exitPointId = $get('exit_point_id');
        $vehicleClass = $get('vehicle_class');
        $paymentMethod = $get('payment_method');

        // Check if we have all required data
        if (! $tollRoadId || ! $entryPointId || ! $exitPointId || ! $vehicleClass) {
            $set('toll_fare', null);

            return;
        }

        // Find the toll fare for this entry/exit combination
        $tollFare = TollFare::where('toll_road_id', $tollRoadId)
            ->where('entry_point_id', $entryPointId)
            ->where('exit_point_id', $exitPointId)
            ->where('is_active', true)
            ->first();

        if (! $tollFare) {
            // Try the reverse direction (some toll systems store only one direction)
            $tollFare = TollFare::where('toll_road_id', $tollRoadId)
                ->where('entry_point_id', $exitPointId)
                ->where('exit_point_id', $entryPointId)
                ->where('is_active', true)
                ->first();
        }

        if ($tollFare) {
            // Determine if using RFID for discount
            $useRfid = in_array($paymentMethod, [
                Dispatchings::PAYMENT_METHOD_AUTOSWEEP,
                Dispatchings::PAYMENT_METHOD_EASYTRIP,
            ]);

            // Calculate fare based on vehicle class and payment method
            $fare = $tollFare->getFare($vehicleClass, $useRfid);
            $set('toll_fare', $fare);
        } else {
            // Set a default fare if no toll fare record is found
            $defaultFare = 0;

            // Log the missing toll fare for debugging
            Log::info("No toll fare found for road {$tollRoadId}, entry {$entryPointId}, exit {$exitPointId}");

            $set('toll_fare', $defaultFare);
        }
    }

    public function getFromLocation(): string
    {
        return $this->record->from_location_display;
    }

    public function getToLocation(): string
    {
        return $this->record->to_location_display;
    }

    public function getFromLocationRaw(): string
    {
        return $this->record->from_location;
    }

    public function getToLocationRaw(): string
    {
        return $this->record->to_location;
    }

    public function getDriverName(): string
    {
        return $this->record->driver?->employee?->full_name ?? '—';
    }

    public function getContactNumber(): ?string
    {
        return $this->record->driver?->contactno ?? null;
    }

    public function getPlateNumber(): ?string
    {
        return $this->record->vehicle?->plate_number ?? '—';
    }

    public function getOdometerOut(): ?int
    {
        $odometer = Odometer::where('dispatch_id', $this->record->id)->first();

        return $odometer?->odometer_out;
    }

    public function getOdometerIn(): ?int
    {
        $odometer = Odometer::where('dispatch_id', $this->record->id)->first();

        return $odometer?->odometer_in;
    }

    public function getDistanceTraveled(): ?int
    {
        $odometer = Odometer::where('dispatch_id', $this->record->id)->first();
        if ($odometer && $odometer->odometer_out && $odometer->odometer_in) {
            return $odometer->odometer_out - $odometer->odometer_in;
        }

        return null;
    }

    public function getDepartureTime(): string
    {
        return $this->record->departure_time->format('M d, Y h:i A');
    }

    public function getArrivalTime(): ?string
    {
        return $this->record->arrival_time ? $this->record->arrival_time->format('M d, Y h:i A') : null;
    }

    public function getCancellationRecord(): ?string
    {
        return $this->record->cancellation_reason ? $this->record->cancellation_reason : null;
    }

    public function getGasConsumption(): ?string
    {
        return $this->record->gas_consumption ? $this->record->gas_consumption : null;
    }

    public function getGasRefillType(): ?string
    {
        return $this->record->gas_refill_type ? $this->record->gas_refill_type : null;
    }

    public function getGasReceipt(): ?string
    {
        return $this->record->gas_receipt ? $this->record->gas_receipt : null;
    }

    public function getStatus(): string
    {
        return $this->record->status;
    }

    public function getEnrouteTime(): string
    {
        return $this->record->en_route_time ?
            Carbon::parse($this->record->en_route_time)->setTimezone('Asia/Manila')->format('h:i A M d, Y') :
            'Not yet en route';
    }

    public function getCompletedTime(): string
    {
        return $this->record->complete_time ?
            Carbon::parse($this->record->complete_time)->setTimezone('Asia/Manila')->format('h:i A M d, Y') :
            'Not yet completed';
    }

    public function getStatusColor(): string
    {
        return $this->record->getStatusColorAttribute();
    }

    public function getVehicleType(): ?string
    {
        return $this->record->vehicle?->vehicle_type;
    }

    public function getFromCoordinates(): ?array
    {
        return $this->fromCoordinates;
    }

    public function getToCoordinates(): ?array
    {
        return $this->toCoordinates;
    }

    public function getVehicleCategory(): string
    {
        return $this->record->vehicle?->vehicle_category;
    }

    public function getVehicleModel(): string
    {
        return $this->record->vehicle?->model;
    }

    public function getBusinessUnit(): string
    {
        return $this->record->vehicle?->business_unit;
    }

    public function getProjectDescription(): string
    {
        return $this->record->vehicle?->project_description;
    }

    public function getCompanyNumber(): string
    {
        return $this->record->vehicle?->company_no;
    }

    public function getVehicleGroup(): string
    {
        return $this->record->vehicle?->group;
    }

    public function getVehicleGroupColor(): string
    {
        $group = $this->getVehicleGroup();

        return match ($group) {
            Vehicles::GROUP_INTERNAL => 'success',
            Vehicles::GROUP_EXTERNAL => 'primary',
            default => 'gray',
        };
    }

    public function getEstimatedDistance(): ?string
    {
        // If we have both coordinates, we can calculate an estimated distance
        if ($this->fromCoordinates && $this->toCoordinates) {
            // Calculate distance using Haversine formula
            $lat1 = $this->fromCoordinates[0];
            $lon1 = $this->fromCoordinates[1];
            $lat2 = $this->toCoordinates[0];
            $lon2 = $this->toCoordinates[1];

            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $kilometers = $miles * 1.609344;

            return round($kilometers, 2);
        }

        return null;
    }

    public function getEstimatedDuration(): ?string
    {
        // If we have an estimated distance, we can estimate duration
        // assuming an average speed of 50 km/h
        if ($this->getEstimatedDistance()) {
            $distanceInKm = floatval($this->fromCoordinates && $this->toCoordinates ?
                $this->calculateDistance($this->fromCoordinates[0], $this->fromCoordinates[1], $this->toCoordinates[0], $this->toCoordinates[1]) : 0);

            if ($distanceInKm > 0) {
                $hours = $distanceInKm / 30; // Assuming 30 km/h average speed
                $minutes = $hours * 60;

                if ($minutes < 60) {
                    return round($minutes).' min (estimated)';
                } else {
                    $hrs = floor($hours);
                    $mins = round(($hours - $hrs) * 60);

                    return $hrs.' h '.$mins.' min (estimated)';
                }
            }
        }

        return null;
    }

    public function getTripDuration(): ?string
    {
        if ($this->record->isCompleted()) {
            return $this->record->formatted_duration;
        }

        return null;
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $kilometers = $miles * 1.609344;

        return round($kilometers, 2);
    }

    public function getTollCost(): ?string
    {
        return $this->record->toll_cost ? number_format($this->record->toll_cost, 2) : null;
    }

    public function getFuelCost(): ?string
    {
        return $this->record->fuel_cost ? number_format($this->record->fuel_cost, 2) : null;
    }

    public function getTotalCost(): ?string
    {
        return $this->record->total_cost ? number_format($this->record->total_cost, 2) : null;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->record->payment_method ?
            Dispatchings::paymentMethodOptions()[$this->record->payment_method] ?? $this->record->payment_method : null;
    }

    public function getFuelEfficiency(): ?string
    {
        return $this->record->fuel_efficiency ? number_format($this->record->fuel_efficiency, 2) : null;
    }

    public function getFuelPrice(): ?string
    {
        return $this->record->fuel_price ? number_format($this->record->fuel_price, 2) : null;
    }

    public function getTollPoints(): array
    {
        // Get toll records from the tolls table
        $tolls = Toll::where('dispatch_id', $this->record->id)
            ->with(['tollRoad', 'entryPoint', 'exitPoint'])
            ->get();

        return $tolls->map(function ($toll) {
            return [
                'id' => $toll->id,
                'highway' => $toll->tollRoad?->name ?? 'Unknown Highway',
                'entry_point' => $toll->entryPoint?->name ?? 'Unknown Entry',
                'exit_point' => $toll->exitPoint?->name ?? 'Unknown Exit',
                'vehicle_class' => $toll->vehicle_class,
                'payment_method' => Dispatchings::paymentMethodOptions()[$toll->payment_method] ?? $toll->payment_method,
                'fare' => $toll->toll_fare,
                'attachments' => $toll->toll_attachments,
            ];
        })->toArray();
    }

    public function getTotalTollCost(): ?string
    {
        $total = Toll::where('dispatch_id', $this->record->id)->sum('toll_fare');

        return $total > 0 ? number_format($total, 2) : null;
    }

    public function getFuelRecord(): ?array
    {
        $fuel = Fuel::where('dispatch_id', $this->record->id)->first();
        if (! $fuel) {
            return null;
        }

        return [
            'awf' => $fuel->AWF,
            'liter' => $fuel->liter,
            'type' => $fuel->type,
            'attachment' => $fuel->attachment,
        ];
    }

    public function getAWF(): ?string
    {
        $fuel = Fuel::where('dispatch_id', $this->record->id)->first();

        return $fuel?->AWF;
    }

    public function getFuelLiter(): ?string
    {
        $fuel = Fuel::where('dispatch_id', $this->record->id)->first();

        return $fuel?->liter ? number_format($fuel->liter, 2) : null;
    }

    public function getFuelType(): ?string
    {
        $fuel = Fuel::where('dispatch_id', $this->record->id)->first();

        return $fuel?->type;
    }

    /**
     * Send a notification with specified type
     */
    protected function notify(string $message, string $type = 'info'): void
    {
        Notification::make()
            ->title($message)
            ->duration(10000) // Changed from seconds(10)
            ->status($type) // Changed from color()
            ->send();
    }

    public function notifySuccess(string $message): void
    {
        $this->notify($message, 'success');
    }

    public function notifyInfo(string $message): void
    {
        $this->notify($message, 'info');
    }

    public function notifyDanger(string $message): void
    {
        $this->notify($message, 'danger');
    }

    public function redirectToRoute(): void
    {
        $this->redirect(DispatchingResource::getUrl('view-route', ['record' => $this->record]));
    }
}
