<?php

namespace App\Filament\Pages\Widgets\User;

use App\Filament\Resources\AttendanceAuthorizationForms\AttendanceAuthorizationFormResource;
use App\Models\AttendanceAuthItem;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UserAttendanceAuthTable extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Latest Attendance Authorization Correction';

    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn (): Builder => AttendanceAuthItem::query()
                    ->with('attendanceAuth')
                    ->whereHas('attendanceAuth', function ($query) {
                        $query->where('empNo', Auth::user()?->EmpNo ?? Auth::user()?->empNo);
                    })
                    ->latest('date')
            )
            ->columns([
                TextColumn::make('date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('time_range')
                    ->label('Time In / Out')
                    ->state(function (AttendanceAuthItem $record): string {
                        $in = $record->request_time_in ? Carbon::parse($record->request_time_in)->format('h:i A') : '—';
                        $out = $record->request_time_out ? Carbon::parse($record->request_time_out)->format('h:i A') : '—';

                        if ($in === '—' && $out === '—') {
                            return '—';
                        }

                        return "{$in} - {$out}";
                    }),
                TextColumn::make('attendanceAuth.status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        default => 'warning',
                    })
                    ->label('Status'),
                TextColumn::make('attendanceAuth.updated_at')
                    ->label('Date Actioned')
                    ->date()
                    ->sortable()
                    ->placeholder('Pending Action'),
            ])
            ->deferLoading()
            ->emptyStateHeading('No Attendance Authorization Requests yet')
            ->emptyStateDescription('Once you create your first Attendance Authorization request, it will appear here.')
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('view_all')
                    ->label('View All')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(AttendanceAuthorizationFormResource::getUrl('index')),
            ])
            ->recordActions([
                Action::make('open')
                    ->label('View')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (AttendanceAuthItem $record): ?string => $record->attendanceAuth
                            ? AttendanceAuthorizationFormResource::getUrl('edit', ['record' => $record->attendanceAuth])
                            : null
                    ),
                // ->visible(fn (AttendanceAuthItem $record): bool => $record->attendanceAuth?->status !== 'Approved'),
            ]);
    }
}
