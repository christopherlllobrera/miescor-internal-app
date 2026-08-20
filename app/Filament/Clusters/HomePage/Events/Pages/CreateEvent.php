<?php

namespace App\Filament\Clusters\HomePage\Events\Pages;

use App\Filament\Clusters\HomePage\Events\EventResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        $recipient = auth()->user();

        return Notification::make()
            ->success()
            ->title('Event Created')
            ->body('The event has been created successfully.')
            ->sendToDatabase($recipient);
    }
}
