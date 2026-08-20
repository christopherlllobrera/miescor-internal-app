<?php

namespace App\Filament\Clusters\Workflow\WorkflowModules\Pages;

use App\Filament\Clusters\Workflow\WorkflowModules\WorkflowModuleResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditWorkflowModule extends EditRecord
{
    protected static string $resource = WorkflowModuleResource::class;

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
            ->title('Workflow updated')
            ->body('The workflow has been updated successfully.');
    }
}
