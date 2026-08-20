<?php

namespace App\Filament\Clusters\Workflow;

use Filament\Clusters\Cluster;
use UnitEnum;

class WorkflowCluster extends Cluster
{
    protected static string|UnitEnum|null $navigationGroup = 'Employee Portal';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Workflows';

    protected static ?string $slug = 'workflows';

    protected static ?string $clusterBreadcrumb = 'Workflow Pages';
}
