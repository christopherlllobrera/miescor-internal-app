<?php

namespace App\Filament\Resources\PayrollSelfService\LeaveRequests\Tables;

use App\Filament\Resources\PayrollSelfService\Exports\LeaveRequestExporter;
use App\Models\LeaveRequest;
use App\Services\TextExportService;
use Carbon\Carbon;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeaveRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.full_name')
                    ->label('Employee Name'),
                TextColumn::make('employee_group')
                    ->searchable()
                    ->label('Employee Group'),
                TextColumn::make('date_start')
                    ->date()
                    ->label('Start')
                    ->sortable(),
                TextColumn::make('date_end')
                    ->date()
                    ->label('End')
                    ->sortable(),
                TextColumn::make('days_total')
                    ->searchable()
                    ->label('Total'),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Date Filled')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('date_range')
                    ->schema([
                        DatePicker::make('from')
                            ->label('Start Date'),
                        DatePicker::make('until')
                            ->label('End Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn (Builder $q, $date) => $q->whereDate('date_start', '>=', $date))
                            ->when($data['until'] ?? null, fn (Builder $q, $date) => $q->whereDate('date_end', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['from'] ?? null) {
                            $indicators['from'] = 'Leaves from: '.Carbon::parse($data['from'])->toFormattedDateString();
                        }
                        if ($data['until'] ?? null) {
                            $indicators['until'] = 'Leaves until: '.Carbon::parse($data['until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->label('Export to Excel / CSV')
                        ->exporter(LeaveRequestExporter::class),
                    BulkAction::make('export_selected_text')
                        ->label('Export to Text (.txt)')
                        ->icon('heroicon-o-document-text')
                        ->action(function (Collection $records): StreamedResponse {
                            $records->loadMissing(['employee', 'location']);

                            $headers = [
                                'ID',
                                'Employee No',
                                'Employee Name',
                                'Employee Group',
                                'Sub Area',
                                'Leave Type',
                                'Start Date',
                                'End Date',
                                'Days Total',
                                'Status',
                                'Date Filed',
                            ];

                            $filename = 'leave_requests_selected_'.now()->format('Ymd_His').'.txt';

                            return TextExportService::streamCollection($filename, $headers, $records, function (LeaveRequest $record): array {
                                return [
                                    $record->id,
                                    $record->empNo,
                                    $record->employee?->full_name ?? '—',
                                    $record->employee_group ?? '',
                                    $record->location?->LocDesc ?? '',
                                    $record->type ?? '',
                                    $record->date_start ?? '',
                                    $record->date_end ?? '',
                                    $record->days_total ?? '',
                                    $record->status ?? '',
                                    $record->created_at?->format('Y-m-d H:i:s') ?? '',
                                ];
                            });
                        }),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
