<?php

namespace App\Filament\Clusters\Workflow\WorkflowTagModules\Pages;

use App\Filament\Clusters\Workflow\WorkflowTagModules\WorkflowTagModuleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Width;

class ListWorkflowTagModules extends ListRecords
{
    protected static string $resource = WorkflowTagModuleResource::class;

    protected static ?string $title = 'Tags';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create Tag')
                ->modalWidth(Width::Medium),
        ];
    }
}
