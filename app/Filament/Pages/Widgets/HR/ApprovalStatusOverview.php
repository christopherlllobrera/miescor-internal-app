<?php

namespace App\Filament\Pages\Widgets\HR;

use App\Filament\Resources\AttendanceAuthorizationForms\AttendanceAuthorizationFormResource;
use App\Filament\Resources\LeaveRequests\LeaveRequestResource;
use App\Filament\Resources\OvertimeRequests\OvertimeRequestResource;
use App\Models\AttendanceAuth;
use App\Models\LeaveRequest;
use App\Models\OvertimeRequest;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ApprovalStatusOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Approval Status';

    protected int|array|null $columns = [
        'md' => 2,
        'xl' => 4,
    ];

    protected function getStats(): array
    {
        return [
            Stat::make('Overtime', OvertimeRequest::query()->where('status', 'Pending')->count())
                ->description('Pending')
                ->icon(Heroicon::OutlinedClock)
                ->color('info')
                ->url(OvertimeRequestResource::getUrl('index')),
            Stat::make('Overtime', OvertimeRequest::query()->where('status', 'Approved')->count())
                ->description('Approved')
                ->icon(Heroicon::OutlinedClock)
                ->color('success')
                ->url(OvertimeRequestResource::getUrl('index')),
            Stat::make('Leave', LeaveRequest::query()->where('status', 'Pending')->count())
                ->description('Pending')
                ->icon(Heroicon::OutlinedCalendar)
                ->color('info')
                ->url(LeaveRequestResource::getUrl('index')),
            Stat::make('Leave', LeaveRequest::query()->where('status', 'Approved')->count())
                ->description('Approved')
                ->icon(Heroicon::OutlinedCalendar)
                ->color('success')
                ->url(LeaveRequestResource::getUrl('index')),
            Stat::make('AAF', AttendanceAuth::query()->where('status', 'Pending')->count())
                ->description('Pending')
                ->icon(Heroicon::OutlinedDocumentText)
                ->color('info')
                ->url(AttendanceAuthorizationFormResource::getUrl('index')),
            Stat::make('AAF', AttendanceAuth::query()->where('status', 'Approved')->count())
                ->description('Approved')
                ->icon(Heroicon::OutlinedDocumentText)
                ->color('success')
                ->url(AttendanceAuthorizationFormResource::getUrl('index')),
        ];
    }
}
