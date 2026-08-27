<?php

namespace App\Filament\Pages\Dashboard;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class HRDashboard extends Page
{
    protected static ?string $title = 'HR Dashboard';

    protected static string $routePath = 'hr-dashboard';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?int $navigationSort = 2;
}
