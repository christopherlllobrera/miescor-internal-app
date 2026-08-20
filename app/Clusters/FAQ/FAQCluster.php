<?php

namespace App\Filament\Clusters\FAQ;

use Filament\Clusters\Cluster;
use UnitEnum;

class FAQCluster extends Cluster
{
    protected static string|UnitEnum|null $navigationGroup = 'Employee Portal';

    protected static ?string $navigationLabel = 'FAQs';

    protected static ?int $navigationSort = 4;

    protected static ?string $clusterBreadcrumb = 'FAQs';
}
