<?php

namespace App\Filament\Clusters\Workflow\WorkflowTagModules\Pages;

use App\Filament\Clusters\Workflow\WorkflowTagModules\WorkflowTagModuleResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditWorkflowTagModule extends EditRecord
{
    protected static string $resource = WorkflowTagModuleResource::class;

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
            ->title('Workflow Tag updated')
            ->body('The workflow tag has been updated successfully.');
    }
}
