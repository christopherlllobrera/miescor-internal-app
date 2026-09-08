<?php

namespace App\Filament\Resources\Contracts\Pages;

use App\Filament\Resources\Contracts\ContractResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContract extends EditRecord
{
    protected static string $resource = ContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (isset($data['reference_no'])) {
            $parts = explode('-', $data['reference_no']);
            $data['reference_no'] = end($parts);
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $prefix = 'COCO-'.($data['proponent'] ?? '___').'-'.date('y').'-';
        $data['reference_no'] = $prefix.$data['reference_no'];

        $data['updated_by'] = auth()->id();

        return $data;
    }
}
