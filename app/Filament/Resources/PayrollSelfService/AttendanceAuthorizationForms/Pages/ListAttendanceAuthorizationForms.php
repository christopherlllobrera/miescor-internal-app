<?php

namespace App\Filament\Resources\PayrollSelfService\AttendanceAuthorizationForms\Pages;

use App\Filament\Resources\PayrollSelfService\AttendanceAuthorizationForms\AttendanceAuthorizationFormResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAttendanceAuthorizationForms extends ListRecords
{
    protected static string $resource = AttendanceAuthorizationFormResource::class;

    protected static ?string $title = 'Attendance Authorization';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Create'),
        ];
    }
}
