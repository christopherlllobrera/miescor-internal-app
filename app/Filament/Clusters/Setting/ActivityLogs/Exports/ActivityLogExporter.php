<?php

namespace App\Filament\Clusters\Setting\ActivityLogs\Exports;

use App\Models\ActivityLog;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class ActivityLogExporter extends Exporter
{
    protected static ?string $model = ActivityLog::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('log_name'),
            ExportColumn::make('description'),
            ExportColumn::make('subject_type'),
            ExportColumn::make('event'),
            ExportColumn::make('subject_id'),
            ExportColumn::make('causer_type'),
            ExportColumn::make('causer_id'),
            ExportColumn::make('properties')
                ->formatStateUsing(fn ($state) => is_array($state)
                    ? json_encode($state, JSON_UNESCAPED_UNICODE)
                    : $state),
            ExportColumn::make('batch_uuid'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your activity log export has completed and '.Number::format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
