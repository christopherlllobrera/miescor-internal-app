<?php

namespace App\Filament\Resources\PayrollSelfService\OvertimeRequests\Pages;

use App\Filament\Resources\PayrollSelfService\OvertimeRequests\OvertimeRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOvertimeRequests extends ListRecords
{
    protected static string $resource = OvertimeRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Create OT Request'),
        ];
    }
}
