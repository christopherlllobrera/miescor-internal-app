<?php

namespace App\Filament\Pages\Widgets\HR;

use App\Models\AttendanceAuthItem;
use Carbon\Carbon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class AttendanceAuthorizationFormTable extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'AAF';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn (): Builder => AttendanceAuthItem::query()
                    ->with('attendanceAuth')
                    ->latest('date')
            )
            ->columns([
                TextColumn::make('date')
                    ->label('Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('time')
                    ->label('Time')
                    ->state(function (AttendanceAuthItem $record): string {
                        $timeIn = $record->request_time_in
                            ? Carbon::parse($record->request_time_in)->format('h:i A')
                            : '—';

                        $timeOut = $record->request_time_out
                            ? Carbon::parse($record->request_time_out)->format('h:i A')
                            : '—';

                        return "{$timeIn} - {$timeOut}";
                    }),

                TextColumn::make('attendanceAuth.status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        default => 'warning',
                    }),

                TextColumn::make('attendanceAuth.updated_at')
                    ->label('Date Approved')
                    ->date()
                    ->placeholder('Pending Action'),
            ])
            ->emptyStateHeading('No data available');
    }
}
