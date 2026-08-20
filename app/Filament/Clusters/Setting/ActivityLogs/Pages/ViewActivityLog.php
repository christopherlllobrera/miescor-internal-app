<?php

namespace App\Filament\Clusters\Setting\ActivityLogs\Pages;

use App\Filament\Clusters\Setting\ActivityLogs\ActivityLogResource;
use Filament\Resources\Pages\ViewRecord;

class ViewActivityLog extends ViewRecord
{
    protected static string $resource = ActivityLogResource::class;

    protected static ?string $title = 'View Activity Log';

    protected ?string $heading = 'View Activity Log';

    protected static ?string $breadcrumb = 'View Activity';
}
