<?php

namespace App\Filament\Pages\Dashboard;

use App\Filament\Pages\Widgets\User\LeaveWidgetTable;
use App\Filament\Pages\Widgets\User\UserAttendanceAuthTable;
use App\Filament\Pages\Widgets\User\UserOvertimeTable;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class UserDashboard extends Page
{
    protected static ?string $title = 'User Dashboard';

    protected static string $routePath = 'user-dashboard';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?int $navigationSort = 1;

    public function getHeaderWidgets(): array
    {
        return [
            UserOvertimeTable::class,
            LeaveWidgetTable::class,
            UserAttendanceAuthTable::class,

        ];
    }
}
