<?php

namespace App\Filament\Clusters\NewsPage\Resources\CategoryResource\Pages;

use App\Filament\Clusters\NewsPage\Resources\CategoryResource\CategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;
}
