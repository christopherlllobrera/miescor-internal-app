<?php

namespace App\Filament\Clusters\Workflow\WorkflowTagModules\Pages;

use App\Filament\Clusters\Workflow\WorkflowTagModules\WorkflowTagModuleResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkflowTagModule extends CreateRecord
{
    protected static string $resource = WorkflowTagModuleResource::class;

    protected static ?string $title = 'Create Workflow Tag';

    public function canCreateAnother(): bool
    {
        return false;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Workflow Tag created')
            ->body('The workflow tag has been created successfully.');
    }
}
