<?php

namespace App\Filament\Resources\PayrollSelfService\Exports;

use App\Models\LeaveRequest;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Str;

class LeaveRequestExporter extends Exporter
{
    protected static ?string $model = LeaveRequest::class;

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
            ExportColumn::make('location.LocDesc')
                ->label('Sub Area / Location'),
            ExportColumn::make('schedule')
                ->label('Schedule'),
            ExportColumn::make('type')
                ->label('Leave Type'),
            ExportColumn::make('date_start')
                ->label('Start Date'),
            ExportColumn::make('date_end')
                ->label('End Date'),
            ExportColumn::make('days_total')
                ->label('Days Total'),
            ExportColumn::make('duration')
                ->label('Duration'),
            ExportColumn::make('available_credits')
                ->label('Available Credits'),
            ExportColumn::make('vl_balance')
                ->label('VL Balance'),
            ExportColumn::make('sl_balance')
                ->label('SL Balance'),
            ExportColumn::make('reason')
                ->label('Reason'),
            ExportColumn::make('status')
                ->label('Status'),
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
            Grid::make(2)
                ->schema([
                    DatePicker::make('start_date')
                        ->label('From Date'),
                    DatePicker::make('end_date')
                        ->label('Until Date'),
                    Select::make('business_unit')
                        ->label('Business Unit')
                        ->options(BusinessUnits::pluck('BusinessUnitDesc', 'BusinessUnitNo'))
                        ->searchable()
                        ->preload(),
                    Select::make('payroll_area')
                        ->label('Payroll Area')
                        // ->options(PayrollArea::pluck('PayrollAreaDesc', 'PayrollAreaNo'))
                        ->searchable()
                        ->preload(),
                ]),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your leave request export has completed and '.Str::of('row')->counted($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Str::of('row')->counted($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
