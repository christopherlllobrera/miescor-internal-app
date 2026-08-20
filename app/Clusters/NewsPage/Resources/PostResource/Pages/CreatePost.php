<?php

namespace App\Filament\Clusters\NewsPage\Resources\PostResource\Pages;

use App\Filament\Clusters\NewsPage\Resources\PostResource\PostResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;
}
