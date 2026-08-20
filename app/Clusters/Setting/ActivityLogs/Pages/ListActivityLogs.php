<?php

namespace App\Filament\Clusters\Setting\ActivityLogs\Pages;

use App\Filament\Clusters\Setting\ActivityLogs\Actions\Cleanlogs;
use App\Filament\Clusters\Setting\ActivityLogs\ActivityLogResource;
use App\Filament\Clusters\Setting\ActivityLogs\Exports\ActivityLogExporter;
use App\Filament\Clusters\Setting\ActivityLogs\Widgets\ActivityOverview;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;

class ListActivityLogs extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->exporter(ActivityLogExporter::class)
                ->columnMappingColumns(2),
            Cleanlogs::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ActivityOverview::class,

        ];
    }
}
