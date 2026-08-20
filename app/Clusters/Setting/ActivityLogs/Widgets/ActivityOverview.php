<?php

namespace App\Filament\Clusters\Setting\ActivityLogs\Widgets;

use App\Models\ActivityLog;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ActivityOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Activity Log Overview';

    protected ?string $description = 'A quick summary of your activity logs';

    protected ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $now = now();

        $total = ActivityLog::count();
        $today = ActivityLog::whereDate('created_at', Carbon::today())->count();
        $week = ActivityLog::whereBetween('created_at', [Carbon::now()->startOfWeek(), $now])->count();
        $month = ActivityLog::whereBetween('created_at', [Carbon::now()->startOfMonth(), $now])->count();

        // Build mini chart data manually (last 7 days)
        $chartData = ActivityLog::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
            ->where('created_at', '>=', $now->copy()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total')
            ->toArray();

        // Ensure chart has consistent 7 values (for days with no activity)
        $chart = [];
        foreach (range(6, 0) as $i) {
            $day = $now->copy()->subDays($i)->toDateString();
            $chart[] = $chartData[$day] ?? 0;
        }

        return [
            Stat::make('Total Activities', number_format($total))
                ->description('All time')
                ->color('info')
                ->chart($chart),
            Stat::make('Today\'s Activities', number_format($today))
                ->description('Last 24 hours')
                ->color('success')
                ->chart($chart),
            Stat::make('This Week', number_format($week))
                ->description('Last 7 days')
                ->color('primary')
                ->chart($chart),
            Stat::make('This Month', number_format($month))
                ->description('Last 30 days')
                ->color('gray')
                ->chart($chart),
        ];
    }
}
