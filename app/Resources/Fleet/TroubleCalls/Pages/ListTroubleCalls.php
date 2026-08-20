<?php

namespace App\Filament\Resources\Fleet\TroubleCalls\Pages;

use App\Filament\Pages\Widgets\TroubleCallOverview;
use App\Filament\Resources\Fleet\TroubleCalls\TroubleCallResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTroubleCalls extends ListRecords
{
    protected static string $resource = TroubleCallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TroubleCallOverview::class,
        ];
    }
}
