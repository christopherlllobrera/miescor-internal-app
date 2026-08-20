<?php

namespace App\Filament\Clusters\NewsPage\Resources\CommentResource\Pages;

use App\Filament\Clusters\NewsPage\Resources\CommentResource\CommentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditComment extends EditRecord
{
    protected static string $resource = CommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
