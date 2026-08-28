<?php

namespace App\Filament\Resources\AttendanceAuthorizationForms\Pages;

use App\Filament\Resources\AttendanceAuthorizationForms\AttendanceAuthorizationFormResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateAttendanceAuthorizationForm extends CreateRecord
{
    protected static string $resource = AttendanceAuthorizationFormResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): Notification
    {
        return Notification::make()
            ->title('New AAF')
            ->success()
            ->body('A new Attendance Authorization has been created')
            ->send();
    }

    protected static ?string $title = 'Create AAF';
}
