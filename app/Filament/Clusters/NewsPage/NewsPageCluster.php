<?php

namespace App\Filament\Clusters\NewsPage;

use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class NewsPageCluster extends Cluster
{
    // protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static string|UnitEnum|null $navigationGroup = 'Employee Portal';

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationLabel = 'News & Features';

    protected static ?string $slug = 'news-feature-pages';

    protected static ?string $clusterBreadcrumb = 'News Pages';
}
