<?php

namespace App\Filament\Resources\Fleet\Dispatchings\Exports;

use App\Models\Dispatchings;
use Carbon\Carbon;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class DispatchingsExporter extends Exporter
{
    protected static ?string $model = Dispatchings::class;

    public static function getColumns(): array
    {
        return [
            // === COLUMNS MATCHING EXCEL ORDER ===
            ExportColumn::make('id')
                ->label('ID'),
            // 1. DRIVER
            ExportColumn::make('driver.employee.full_name')
                ->label('Driver'),

            // 2. DUTY SHIFT (combined format like "7AM-4PM")
            ExportColumn::make('duty_shift')
                ->label('Duty Shift')
                ->getStateUsing(function ($record) {
                    $shiftIn = $record->driver?->shift_in;
                    $shiftOut = $record->driver?->shift_out;
                    if ($shiftIn && $shiftOut) {
                        return $shiftIn.'-'.$shiftOut;
                    }

                    return null;
                }),

            // 3. PLATE NUMBER
            ExportColumn::make('vehicle.plate_number')
                ->label('Plate Number'),

            // 4. Sched In
            ExportColumn::make('driver.shift_in')
                ->label('Sched In'),

            // 5. Sched Out
            ExportColumn::make('driver.shift_out')
                ->label('Sched Out'),

            // 6. Gas Withdrawn (Fuel Liters)
            ExportColumn::make('fuel.liter')
                ->label('Gas Withdrawn'),

            // 7. Odometer Out
            ExportColumn::make('odometer.odometer_out')
                ->label('Odometer Out'),

            // 8. Odometer In
            ExportColumn::make('odometer.odometer_in')
                ->label('Odometer In'),

            // 9. COST CENTER
            ExportColumn::make('requestingOffice.cost_center')
                ->label('Cost Center'),

            // 10. REQUESTING OFFICE
            ExportColumn::make('requestingOffice.requestor_office')
                ->label('Requesting Office'),

            // 11. PASSENGERS
            ExportColumn::make('passengers_list')
                ->label('Passengers')
                ->getStateUsing(function ($record) {
                    return $record->passengers->map(function ($passenger) {
                        return $passenger->passenger_name;
                    })->implode('; ');
                }),

            // 12. No. of Passenger
            ExportColumn::make('passenger_count')
                ->label('No. of Passenger'),

            // 13. Month
            ExportColumn::make('created_month')
                ->label('Month')
                ->getStateUsing(fn ($record) => $record->created_at ? Carbon::parse($record->created_at)->format('F') : null),

            // 14. Date
            ExportColumn::make('created_date')
                ->label('Date')
                ->getStateUsing(fn ($record) => $record->created_at ? Carbon::parse($record->created_at)->format('j-M') : null),

            // 15. REQUESTED TIME (Departure Time)
            ExportColumn::make('departure_time')
                ->label('Requested Time'),

            // 16. OUT (En route)
            ExportColumn::make('en_route_time')
                ->label('Out'),

            // 17. IN (Complete)
            ExportColumn::make('complete_time')
                ->label('In'),

            // 18. RENDERED HOURS
            ExportColumn::make('rendered_hour')
                ->label('Rendered Hours'),

            // 19. PICK UP-POINT
            ExportColumn::make('from_location')
                ->label('Pick Up-Point'),

            // 20. DESTINATION/LOCATION
            ExportColumn::make('to_location')
                ->label('Destination/Location'),

            // 21. PURPOSE
            ExportColumn::make('purpose')
                ->label('Purpose'),

            // 22. REASON
            ExportColumn::make('reason')
                ->label('Reason'),

            // === ADDITIONAL COLUMNS (Not in Excel) ===

            // Core Fields

            ExportColumn::make('vea_ticket_number')
                ->label('VEA Ticket #'),
            ExportColumn::make('request_item')
                ->label('Request Item'),
            ExportColumn::make('status')
                ->label('Status'),
            ExportColumn::make('priority_level')
                ->label('Priority'),
            ExportColumn::make('cancel_time')
                ->label('Cancel Time'),

            // Driver Additional Info
            ExportColumn::make('driver.empNo')
                ->label('Personnel Number'),
            ExportColumn::make('driver.contactno')
                ->label('Contact Number'),
            ExportColumn::make('out_of_service')
                ->label('Out of Service'),

            // Odometer Calculated
            ExportColumn::make('distance_traveled')
                ->label('Distance Traveled (km)')
                ->getStateUsing(fn ($record) => $record->odometer && $record->odometer->odometer_out && $record->odometer->odometer_in
                        ? $record->odometer->odometer_out - $record->odometer->odometer_in
                        : null
                ),

            // Fuel Additional
            ExportColumn::make('fuel.AWF')
                ->label('AWF/SI'),
            ExportColumn::make('fuel.type')
                ->label('Fuel Refill Type'),
            ExportColumn::make('fuel.kilowatt_hour')
                ->label('Kilowatt Hour'),

            // Tolls
            ExportColumn::make('total_toll_cost')
                ->label('Total Toll Cost')
                ->getStateUsing(fn ($record) => $record->tolls()->sum('toll_fare')),
            ExportColumn::make('toll_entries')
                ->label('Toll Entries')
                ->getStateUsing(function ($record) {
                    return $record->tolls->map(function ($toll) {
                        return sprintf(
                            '%s: %s → %s (₱%s)',
                            $toll->tollRoad?->name ?? 'Unknown',
                            $toll->entryPoint?->name ?? 'N/A',
                            $toll->exitPoint?->name ?? 'N/A',
                            number_format($toll->toll_fare ?? 0, 2)
                        );
                    })->implode('; ');
                }),

            // Foreign Keys
            ExportColumn::make('incident_id')
                ->label('Incident ID'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your dispatchings export has completed and '.Number::format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
