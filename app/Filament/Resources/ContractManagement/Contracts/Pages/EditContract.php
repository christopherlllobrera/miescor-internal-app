<?php

namespace App\Filament\Resources\ContractManagement\Contracts\Pages;

use App\Filament\Resources\ContractManagement\Contracts\ContractResource;
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
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['updated_by'] = auth()->id();

        return $data;
    }

    protected function afterSave(): void
    {
        $contract = $this->record;

        if (in_array($contract->status, ['pending', 'proponent-pending']) && $contract->assignees()->exists()) {
            $contract->status = 'in-progress';
            $contract->save();
        }
    }
}
