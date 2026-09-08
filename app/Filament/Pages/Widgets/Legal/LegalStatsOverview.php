<?php

namespace App\Filament\Pages\Widgets\Legal;

use App\Models\Contract;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LegalStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Contracts', Contract::count())
                ->description('All contracts in the system')
                ->descriptionIcon('heroicon-m-document-duplicate')
                ->color('primary'),
            Stat::make('Pending', Contract::where('status', 'pending')->count())
                ->description('Waiting for assignment')
                ->descriptionIcon('heroicon-m-clock')
                ->color('violet'),
            Stat::make('In Progress', Contract::where('status', 'in-progress')->count())
                ->description('Currently being reviewed')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('info'),
            Stat::make('For Approval', Contract::where('status', 'for-approval')->count())
                ->description('Pending final approval')
                ->descriptionIcon('heroicon-m-eye')
                ->color('warning'),
        ];
    }
}
