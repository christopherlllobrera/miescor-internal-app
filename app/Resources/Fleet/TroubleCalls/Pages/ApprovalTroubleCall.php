<?php

namespace App\Filament\Resources\Fleet\TroubleCalls\Pages;

use App\Filament\Resources\Fleet\TroubleCalls\TroubleCallResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class ApprovalTroubleCall extends EditRecord
{
    protected static string $resource = TroubleCallResource::class;

    public static ?string $title = 'Trouble Call Resolution';

    public static ?string $breadcrumb = 'Trouble Call Resolution';

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
                        ->title('Trouble Call Resolved!')
                        ->success()
                        ->body('The trouble call has been resolved.')
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
