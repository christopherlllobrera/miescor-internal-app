<?php

namespace App\Filament\Pages\Dashboard;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class CBDIDashboard extends Page
{
    protected static ?string $title = 'CBDI Dashboard';

    protected static string $routePath = 'cbdi-dashboard';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?int $navigationSort = 3;
}
