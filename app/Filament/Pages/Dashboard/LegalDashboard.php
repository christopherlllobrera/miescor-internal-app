<?php

namespace App\Filament\Pages\Dashboard;

use App\Filament\Pages\Widgets\Legal\LegalStatsOverview;
use App\Filament\Pages\Widgets\Legal\RecentContractsWidget;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use UnitEnum;

class LegalDashboard extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $title = 'Legal Dashboard';

    protected static string $routePath = 'legal-dashboard';

    protected static ?int $navigationSort = 4;

    public function getHeaderWidgets(): array
    {
        return [
            LegalStatsOverview::class,
            RecentContractsWidget::class,
        ];
    }
}
