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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        $data['updated_by'] = null;

        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $key => $item) {
                $data['items'][$key]['created_by'] = auth()->id();
                $data['items'][$key]['updated_by'] = null;
            }
        }

        return $data;
    }
}
