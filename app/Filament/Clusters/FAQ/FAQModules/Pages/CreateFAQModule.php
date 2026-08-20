<?php

namespace App\Filament\Clusters\FAQ\FAQModules\Pages;

use App\Filament\Clusters\FAQ\FAQModules\FAQModuleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFAQModule extends CreateRecord
{
    protected static string $resource = FAQModuleResource::class;

    protected static bool $canCreateAnother = false;

    protected static ?string $title = 'Create Frequently Asked Question';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
