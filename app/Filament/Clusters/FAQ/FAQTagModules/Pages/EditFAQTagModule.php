<?php

namespace App\Filament\Clusters\FAQ\FAQTagModules\Pages;

use App\Filament\Clusters\FAQ\FAQTagModules\FAQTagModuleResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditFAQTagModule extends EditRecord
{
    protected static string $resource = FAQTagModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('FAQs Tag updated')
            ->body('The FAQs tag has been updated successfully.');
    }
}
