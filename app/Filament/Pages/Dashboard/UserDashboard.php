<?php

namespace App\Filament\Pages\Dashboard;

use App\Filament\Pages\Widgets\PSSAnnouncement;
use App\Filament\Pages\Widgets\User\LeaveWidgetTable;
use App\Filament\Pages\Widgets\User\UserAttendanceAuthTable;
use App\Filament\Pages\Widgets\User\UserOvertimeTable;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class UserDashboard extends Page
{
    protected static ?string $title = 'Payroll Self Service Overview';

    protected static ?string $navigationLabel = 'PSS Overview';

    protected static string $routePath = 'user-dashboard';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?int $navigationSort = 1;

    public function getHeaderWidgets(): array
    {
        return [
            PSSAnnouncement::class,
            UserOvertimeTable::class,
            LeaveWidgetTable::class,
            UserAttendanceAuthTable::class,

        ];
    }
}
