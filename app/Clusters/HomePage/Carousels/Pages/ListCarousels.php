<?php

namespace App\Filament\Clusters\HomePage\Carousels\Pages;

use App\Filament\Clusters\HomePage\Carousels\CarouselResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCarousels extends ListRecords
{
    protected static string $resource = CarouselResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
