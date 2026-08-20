<?php

namespace App\Filament\Clusters\NewsPage\Resources\CommentResource\Pages;

use App\Filament\Clusters\NewsPage\Resources\CommentResource\CommentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateComment extends CreateRecord
{
    protected static string $resource = CommentResource::class;
}
