<?php

namespace App\Filament\Clusters\Workflow\WorkflowModules\Pages;

use App\Filament\Clusters\Workflow\WorkflowModules\WorkflowModuleResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkflowModule extends CreateRecord
{
    protected static string $resource = WorkflowModuleResource::class;

    protected static ?string $title = 'Create Workflow';

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Workflow created')
            ->body('The workflow has been created successfully.');
    }
}
