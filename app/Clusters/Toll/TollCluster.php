<?php

namespace App\Filament\Clusters\Toll;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class TollCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;

    protected static string|UnitEnum|null $navigationGroup = 'Fleet Management';

    protected static ?string $slug = 'toll-management';

    protected static ?string $navigationLabel = 'Toll';

    protected static ?string $clusterBreadcrumb = 'Toll';

    protected static ?int $navigationSort = 4;
}
