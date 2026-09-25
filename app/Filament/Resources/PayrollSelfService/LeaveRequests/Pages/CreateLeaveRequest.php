<?php

namespace App\Filament\Resources\PayrollSelfService\LeaveRequests\Pages;

use App\Filament\Resources\PayrollSelfService\LeaveRequests\LeaveRequestResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Livewire\Attributes\Url;

class CreateLeaveRequest extends CreateRecord
{
    protected static string $resource = LeaveRequestResource::class;

    protected static bool $canCreateAnother = false;

    #[Url]
    public ?string $type = null;

    protected function fillForm(): void
    {
        parent::fillForm();

        if ($this->type) {
            $this->data['type'] = $this->type;
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): Notification
    {
        return Notification::make()
            ->title('New Leave Request')
            ->success()
            ->body('A new Leave Request has been created')
            ->send();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        $data['updated_by'] = null;

        return $data;
    }
}
