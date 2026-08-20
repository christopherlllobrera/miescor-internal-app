<?php

namespace App\Filament\Clusters\NewsPage\Resources\PostResource\Pages;

use App\Filament\Clusters\NewsPage\Resources\PostResource\PostResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPost extends ViewRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
