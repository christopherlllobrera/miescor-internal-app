<?php

namespace App\Filament\Clusters\Workflow\WorkflowModules\Pages;

use App\Filament\Clusters\Workflow\WorkflowModules\WorkflowModuleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkflowModules extends ListRecords
{
    protected static string $resource = WorkflowModuleResource::class;

    protected static ?string $title = 'Workflows';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create Workflow'),
        ];
    }
}
