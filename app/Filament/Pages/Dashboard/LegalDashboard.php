<?php

namespace App\Filament\Pages\Dashboard;

use App\Filament\Pages\Widgets\Legal\LegalStatsOverview;
use App\Filament\Pages\Widgets\Legal\RecentContractsWidget;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class LegalDashboard extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedScale;

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
