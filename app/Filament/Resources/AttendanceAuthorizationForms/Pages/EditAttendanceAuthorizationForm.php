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
}
