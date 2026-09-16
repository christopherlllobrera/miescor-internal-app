<?php

namespace App\Filament\Resources\PayrollSelfService\Exports;

use App\Models\OvertimeRequest;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Str;

class OvertimeRequestExporter extends Exporter
{
    protected static ?string $model = OvertimeRequest::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('empNo')
                ->label('Employee No.'),
            ExportColumn::make('employee.full_name')
                ->label('Employee Name'),
            ExportColumn::make('employee_group')
                ->label('Employee Group'),
            ExportColumn::make('employee.location.LocDesc')
                ->label('Sub Area'),
            ExportColumn::make('schedule')
                ->label('Schedule'),
            ExportColumn::make('status')
                ->label('Status'),
            ExportColumn::make('ot_dates')
                ->label('OT Dates')
                ->state(fn (OvertimeRequest $record): string => $record->items->map(fn ($item) => $item->date?->format('Y-m-d'))->filter()->implode(', ')),
            ExportColumn::make('total_hours')
                ->label('Total Hours')
                ->state(fn (OvertimeRequest $record): string => (string) $record->items->sum('number_of_hours')),
            ExportColumn::make('immediate_supervisor.full_name')
                ->label('Immediate Supervisor'),
            ExportColumn::make('next_level_supervisor.full_name')
                ->label('Next Level Supervisor'),
            ExportColumn::make('remarks')
                ->label('Remarks'),
            ExportColumn::make('created_at')
                ->label('Date Filed'),
        ];
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            DatePicker::make('start_date')
                ->label('From Date'),
            DatePicker::make('end_date')
                ->label('Until Date'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your overtime request export has completed and '.Str::of('row')->counted($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Str::of('row')->counted($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
