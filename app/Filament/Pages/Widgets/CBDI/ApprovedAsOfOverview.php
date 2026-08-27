<?php

namespace App\Filament\Pages\Widgets\CBDI;

use App\Filament\Resources\AttendanceAuthorizationForms\AttendanceAuthorizationFormResource;
use App\Filament\Resources\LeaveRequests\LeaveRequestResource;
use App\Filament\Resources\OvertimeRequests\OvertimeRequestResource;
use App\Models\AttendanceAuth;
use App\Models\LeaveRequest;
use App\Models\OvertimeRequest;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ApprovedAsOfOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Approved As Of';

    protected int|array|null $columns = 1;

    public ?string $approvedFrom = null;

    public ?string $approvedTo = null;

    public function mount(): void
    {
        $this->approvedFrom = now()->startOfMonth()->toDateString();
        $this->approvedTo = now()->toDateString();
    }

    public function getSectionContentComponent(): Component
    {
        return Section::make()
            ->heading($this->getHeading())
            ->schema([
                Grid::make(2)
                    ->schema([
                        DatePicker::make('approvedFrom')
                            ->label('Date From')
                            ->live(),
                        DatePicker::make('approvedTo')
                            ->label('Date To')
                            ->minDate($this->approvedFrom)
                            ->live(),
                    ]),
                ...$this->getCachedStats(),
            ])
            ->columns($this->getColumns())
            ->gridContainer();
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Overtime', $this->getApprovedOvertimeCount())
                ->description('Overtime requests')
                ->icon(Heroicon::OutlinedClock)
                ->color('success')
                ->url(OvertimeRequestResource::getUrl('index', [
                    'tableFilters' => ['status' => ['value' => 'Approved']],
                ])),
            Stat::make('LEAVE', $this->getApprovedLeaveCount())
                ->description('Leave requests')
                ->icon(Heroicon::OutlinedCalendar)
                ->color('info')
                ->url(LeaveRequestResource::getUrl('index', [
                    'tableFilters' => ['status' => ['value' => 'Approved']],
                ])),
            Stat::make('AAF', $this->getApprovedAttendanceAuthorizationCount())
                ->description('Attendance Adjustment Forms')
                ->icon(Heroicon::OutlinedDocumentText)
                ->color('primary')
                ->url(AttendanceAuthorizationFormResource::getUrl('index', [
                    'tableFilters' => ['status' => ['value' => 'Approved']],
                ])),
            Stat::make('CWS', 0)
                ->description('Check Work Sheet')
                ->icon(Heroicon::OutlinedClipboardDocumentCheck)
                ->color('warning'),
        ];
    }

    private function getApprovedOvertimeCount(): int
    {
        return OvertimeRequest::query()
            ->where('status', 'Approved')
            ->when($this->approvedFrom, fn ($query) => $query->whereDate('updated_at', '>=', $this->approvedFrom))
            ->when($this->approvedTo, fn ($query) => $query->whereDate('updated_at', '<=', $this->approvedTo))
            ->count();
    }

    private function getApprovedLeaveCount(): int
    {
        return LeaveRequest::query()
            ->where('status', 'Approved')
            ->when($this->approvedFrom, fn ($query) => $query->whereDate('updated_at', '>=', $this->approvedFrom))
            ->when($this->approvedTo, fn ($query) => $query->whereDate('updated_at', '<=', $this->approvedTo))
            ->count();
    }

    private function getApprovedAttendanceAuthorizationCount(): int
    {
        return AttendanceAuth::query()
            ->where('status', 'Approved')
            ->when($this->approvedFrom, fn ($query) => $query->whereDate('updated_at', '>=', $this->approvedFrom))
            ->when($this->approvedTo, fn ($query) => $query->whereDate('updated_at', '<=', $this->approvedTo))
            ->count();
    }
}
