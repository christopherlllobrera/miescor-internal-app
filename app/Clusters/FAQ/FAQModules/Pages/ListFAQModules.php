<?php

namespace App\Filament\Clusters\FAQ\FAQModules\Pages;

use App\Filament\Clusters\FAQ\FAQModules\FAQModuleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFAQModules extends ListRecords
{
    protected static string $resource = FAQModuleResource::class;

    protected static ?string $title = 'Frequently Asked Questions';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create FAQ'),
        ];
    }
}
