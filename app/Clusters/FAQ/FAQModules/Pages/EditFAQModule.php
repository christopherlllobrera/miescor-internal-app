<?php

namespace App\Filament\Clusters\FAQ\FAQModules\Pages;

use App\Filament\Clusters\FAQ\FAQModules\FAQModuleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFAQModule extends EditRecord
{
    protected static string $resource = FAQModuleResource::class;

    protected static ?string $title = 'Edit Frequently Asked Question';

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
