<?php

namespace App\Filament\Resources\DataManagement\Positions\Pages;

use App\Filament\Resources\DataManagement\Positions\PositionResource;
use Carbon\Carbon;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPosition extends EditRecord
{
    protected static string $resource = PositionResource::class;

    protected static ?string $title = 'Edit Position';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['UpdatedBy'] = auth()->user()->id;
        $data['DateUpdated'] = Carbon::now();

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
