<?php

namespace App\Filament\Clusters\HomePage;

use Filament\Clusters\Cluster;
use UnitEnum;

class HomePageCluster extends Cluster
{
    protected static string|UnitEnum|null $navigationGroup = 'Employee Portal';

    protected static ?int $navigationSort = 7;

    protected static ?string $navigationLabel = 'Home Page';

    protected static ?string $slug = 'home-pages';

    protected static ?string $clusterBreadcrumb = 'Home Page';
}
