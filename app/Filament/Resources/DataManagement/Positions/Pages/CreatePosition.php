<?php

namespace App\Filament\Resources\DataManagement\Positions\Pages;

use App\Filament\Resources\DataManagement\Positions\PositionResource;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;

class CreatePosition extends CreateRecord
{
    protected static string $resource = PositionResource::class;

    protected static ?string $title = 'Create Position';

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['CreatedBy'] = auth()->user()->id ?? null;
        $data['UpdatedBy'] = null;
        $data['DateCreated'] = Carbon::now();
        $data['DateUpdated'] = null;

        return $data;
    }
}
