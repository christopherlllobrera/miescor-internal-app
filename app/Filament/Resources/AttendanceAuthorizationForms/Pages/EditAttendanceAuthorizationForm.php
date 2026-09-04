<?php

namespace App\Filament\Resources\AttendanceAuthorizationForms\Pages;

use App\Filament\Resources\AttendanceAuthorizationForms\AttendanceAuthorizationFormResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAttendanceAuthorizationForm extends EditRecord
{
    protected static string $resource = AttendanceAuthorizationFormResource::class;

    protected static ?string $title = 'Edit AAF';

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['updated_by'] = auth()->id();

        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $key => $item) {
                $data['items'][$key]['updated_by'] = auth()->id();
                if (! isset($item['created_by'])) {
                    $data['items'][$key]['created_by'] = auth()->id();
                }
            }
        }

        return $data;
    }
}
