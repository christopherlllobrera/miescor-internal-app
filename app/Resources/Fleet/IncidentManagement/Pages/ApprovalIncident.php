<?php

namespace App\Filament\Resources\Fleet\IncidentManagement\Pages;

use App\Filament\Resources\Fleet\IncidentManagement\IncidentManagementResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class ApprovalIncident extends EditRecord
{
    protected static string $resource = IncidentManagementResource::class;

    public static ?string $title = 'Incident Resolution';

    public static ?string $breadcrumb = 'Incident Resolution';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Resolved')
                ->label('Resolved')
                ->icon('heroicon-o-check')
                ->color('success')
                ->action(function () {
                    $this->record['status'] = 'Resolved';
                    $this->save();

                    Notification::make()
                        ->title('Incident Resolved!')
                        ->success()
                        ->body('The incident has been resolved')
                        ->send();
                }),

        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return null;
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()->hidden(),
            $this->getCancelFormAction()->hidden(),
        ];
    }
}
