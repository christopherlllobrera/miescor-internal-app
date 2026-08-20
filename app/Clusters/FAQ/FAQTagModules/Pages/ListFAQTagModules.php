<?php

namespace App\Filament\Clusters\FAQ\FAQTagModules\Pages;

use App\Filament\Clusters\FAQ\FAQTagModules\FAQTagModuleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFAQTagModules extends ListRecords
{
    protected static string $resource = FAQTagModuleResource::class;

    protected static ?string $title = 'Tags';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create Tag'),
        ];
    }
}
