<?php

namespace App\Filament\Resources\Fleet\Dispatchings\Pages;

use App\Filament\Resources\Fleet\Dispatchings\DispatchingResource;
use App\Mail\DispatcherNotification;
use App\Models\Dispatchings;
use App\Models\Odometer;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CreateDispatching extends CreateRecord
{
    protected static string $resource = DispatchingResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            // $this->getCreateFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Store odometer data temporarily, remove from main data
        $this->odometerData = [
            'odometer_out' => $data['odometer_out'] ?? null,
            'odometer_in' => $data['odometer_in'] ?? null,
            'vehicle_id' => $data['vehicle_id'] ?? null,
        ];

        unset($data['odometer_out'], $data['odometer_in']);

        return $data;
    }

    protected array $odometerData = [];

    protected function afterCreate(): void
    {
        // Create odometer record if data exists
        if (! empty($this->odometerData['odometer_out']) || ! empty($this->odometerData['odometer_in'])) {
            Odometer::create([
                'dispatch_id' => $this->record->id,
                'vehicle_id' => $this->odometerData['vehicle_id'],
                'odometer_out' => $this->odometerData['odometer_out'],
                'odometer_in' => $this->odometerData['odometer_in'],
            ]);
        }

        // Get the created record
        $dispatching = $this->record;
        $dispatching = Dispatchings::find($dispatching->id);

        // Send email to dispatcher
        Mail::to(Auth::user()->comp_email)->send(new DispatcherNotification($dispatching));
    }

    protected function getCreatedNotification(): Notification
    {
        return Notification::make()
            ->title('New Dispatch')
            ->success()
            ->body('A new dispatch has been created')
            ->send();
    }
}
