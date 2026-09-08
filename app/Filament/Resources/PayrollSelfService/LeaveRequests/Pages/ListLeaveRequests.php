<?php

namespace App\Filament\Resources\PayrollSelfService\LeaveRequests\Pages;

use App\Filament\Resources\PayrollSelfService\LeaveRequests\LeaveRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLeaveRequests extends ListRecords
{
    protected static string $resource = LeaveRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Create Request'),
        ];
    }

    protected static ?string $title = 'Leave Request';
}
