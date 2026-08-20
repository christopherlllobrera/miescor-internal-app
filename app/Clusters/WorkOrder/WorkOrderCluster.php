<?php

namespace App\Filament\Clusters\WorkOrder;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class WorkOrderCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    protected static string|UnitEnum|null $navigationGroup = 'Fleet Management';

    protected static ?string $slug = 'work-order-management';

    protected static ?string $navigationLabel = 'Work Order';

    protected static ?string $clusterBreadcrumb = 'Work Order';

    protected static ?int $navigationSort = 7;
}
