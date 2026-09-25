<?php

namespace App\Filament\Resources\PayrollSelfService\AttendanceAuthorizationForms\Tables;

use App\Filament\Resources\PayrollSelfService\Exports\AttendanceAuthExporter;
use App\Models\AttendanceAuth;
use App\Services\TextExportService;
use Carbon\Carbon;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttendanceAuthorizationFormsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with(['employee.location', 'items']))
            ->columns([
                // TextColumn::make('id')->label('ID'),
                TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->searchable(['EmpFName', 'EmpLName']),
                TextColumn::make('reason')
                    ->label('Reason')
                    ->state(fn (AttendanceAuth $record): string => $record->items->pluck('reason')->filter()->unique()->implode(', ') ?: '—')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('status')
                    ->badge()
                    ->state(fn ($record) => $record->status)
                    ->color(fn (string $state): string => match ($state) {
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        default => 'warning',
                    })
                    ->label('Status'),
                TextColumn::make('created_at')
                    ->label('Date Filed')
                    ->date()
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->deferLoading()
            ->emptyStateHeading('No Attendance Authorization Forms yet')
            ->emptyStateDescription('Once you create your first Attendance Authorization Form, it will appear here.')
            ->filters([
                Filter::make('date_range')
                    ->schema([
                        DatePicker::make('from')
                            ->label('Date Filed From'),
                        DatePicker::make('until')
                            ->label('Date Filed Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn (Builder $q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'] ?? null, fn (Builder $q, $date) => $q->whereDate('created_at', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['from'] ?? null) {
                            $indicators['from'] = 'Filed from: '.Carbon::parse($data['from'])->toFormattedDateString();
                        }
                        if ($data['until'] ?? null) {
                            $indicators['until'] = 'Filed until: '.Carbon::parse($data['until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
                SelectFilter::make('reason')
                    ->label('Reason')
                    ->options([
                        'TCD Malfunction' => 'TCD Malfunction',
                        'Forgot to Log in or Log out' => 'Forgot to Log in or Log out',
                        'Out of Base for Official Business' => 'Out of Base for Official Business',
                        'No Company ID' => 'No Company ID',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'] ?? null,
                            fn (Builder $q, $reason) => $q->whereHas('items', fn (Builder $itemQuery) => $itemQuery->where('reason', $reason))
                        );
                    }),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->columnMappingColumns(3)
                        ->modalHeading('Export Attendance Authorization Records')
                        ->label('Export to Excel / CSV')
                        ->modalWidth(Width::ThreeExtraLarge)
                        ->exporter(AttendanceAuthExporter::class),
                    BulkAction::make('export_selected_text')
                        ->label('Export to Text (.txt)')
                        ->icon('heroicon-o-document-text')
                        ->action(function (Collection $records): StreamedResponse {
                            $records->loadMissing(['employee.location', 'items']);

                            $headers = [
                                'ID',
                                'Employee No',
                                'Employee Name',
                                'Employee Group',
                                'Sub Area',
                                'Reason',
                                'Status',
                                'AAF Dates',
                                'Remarks',
                                'Date Filed',
                            ];

                            $filename = 'attendance_auth_selected_'.now()->format('Ymd_His').'.txt';

                            return TextExportService::streamCollection($filename, $headers, $records, function (AttendanceAuth $record): array {
                                $aafDates = $record->items->map(fn ($item) => $item->date?->format('Y-m-d'))->filter()->implode(', ');

                                return [
                                    $record->id,
                                    $record->empNo,
                                    $record->employee->full_name ?? '—',
                                    $record->employee_group ?? '',
                                    $record->employee->location->LocDesc ?? '',
                                    $record->items->pluck('reason')->filter()->unique()->implode(', '),
                                    $record->status ?? '',
                                    $aafDates,
                                    $record->remarks ?? '',
                                    $record->created_at?->format('Y-m-d H:i:s') ?? '',
                                ];
                            });
                        }),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
