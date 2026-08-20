<?php

namespace App\Filament\Clusters\FAQ\FAQTagModules\Pages;

use App\Filament\Clusters\FAQ\FAQTagModules\FAQTagModuleResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateFAQTagModule extends CreateRecord
{
    protected static string $resource = FAQTagModuleResource::class;

    protected static ?string $title = 'Create FAQs Tag';

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('FAQs Tag created')
            ->body('The FAQs tag has been created successfully.');
    }
}
