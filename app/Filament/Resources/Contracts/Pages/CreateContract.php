<?php

namespace App\Filament\Resources\Contracts\Pages;

use App\Filament\Resources\Contracts\ContractResource;
use App\Models\Contract;
use Filament\Resources\Pages\CreateRecord;

class CreateContract extends CreateRecord
{
    protected static string $resource = ContractResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $prefix = 'COCO-'.($data['proponent'] ?? '___').'-'.date('y').'-';

        $latestContract = Contract::where('reference_no', 'like', $prefix.'%')
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = 1;
        if ($latestContract) {
            $parts = explode('-', $latestContract->reference_no);
            $lastNumber = intval(end($parts));
            $nextNumber = $lastNumber + 1;
        }

        $data['reference_no'] = $prefix.str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        $data['created_by'] = auth()->id();
        $data['updated_by'] = null;

        return $data;
    }
}
