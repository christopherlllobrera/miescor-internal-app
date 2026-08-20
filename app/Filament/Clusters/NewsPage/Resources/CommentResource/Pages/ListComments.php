<?php

namespace App\Filament\Clusters\NewsPage\Resources\CommentResource\Pages;

use App\Filament\Clusters\NewsPage\Resources\CommentResource\CommentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListComments extends ListRecords
{
    protected static string $resource = CommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
