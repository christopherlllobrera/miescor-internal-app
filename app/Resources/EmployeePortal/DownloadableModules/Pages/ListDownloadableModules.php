<?php

namespace App\Filament\Resources\EmployeePortal\DownloadableModules\Pages;

use App\Filament\Resources\EmployeePortal\DownloadableModules\DownloadableModuleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDownloadableModules extends ListRecords
{
    protected static string $resource = DownloadableModuleResource::class;

    protected static ?string $title = 'Downloadable Form';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create Downloadable Form'),
        ];
    }
}
