<?php

namespace App\Filament\Resources\AttendanceAuthorizationForms\Pages;

use App\Filament\Resources\AttendanceAuthorizationForms\AttendanceAuthorizationFormResource;
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
