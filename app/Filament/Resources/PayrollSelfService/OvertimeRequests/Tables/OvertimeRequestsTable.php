<?php

namespace App\Filament\Resources\PayrollSelfService\OvertimeRequests\Tables;

use App\Filament\Resources\PayrollSelfService\Exports\OvertimeRequestExporter;
use App\Models\OvertimeRequest;
use App\Services\TextExportService;
use Carbon\Carbon;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OvertimeRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->searchable(['EmpFName', 'EmpLName']),
                TextColumn::make('employee_group')
                    ->label('Employee Group')
                    ->sortable(),
                TextColumn::make('employee.location.LocDesc')
                    ->label('Sub Area')
                    ->searchable(),
                TextColumn::make('status_display')
                    ->badge()
                    ->state(fn ($record) => $record->status)
                    ->color(fn (string $state): string => match ($state) {
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        default => 'warning',
                    })
                    ->label('Status'),
                SelectColumn::make('status')
                    ->options([
                        'Approved' => 'Approved',
                        'Rejected' => 'Rejected',
                        'Pending' => 'Pending',
                    ]),
                TextColumn::make('created_at')
                    ->label('Date Filed')
                    ->date()
                    ->sortable(),
                TextInputColumn::make('remarks')
                    ->label('Remarks'),
            ])
            ->defaultSort('id', 'desc')
            ->deferLoading()
            ->emptyStateHeading('No Overtime Requests yet')
            ->emptyStateDescription('Once you create your first Overtime Request, it will appear here.')
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
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->label('Export to Excel / CSV')
                        ->exporter(OvertimeRequestExporter::class),
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
                                'Status',
                                'OT Dates',
                                'Total Hours',
                                'Remarks',
                                'Date Filed',
                            ];

                            $filename = 'overtime_requests_selected_'.now()->format('Ymd_His').'.txt';

                            return TextExportService::streamCollection($filename, $headers, $records, function (OvertimeRequest $record): array {
                                $otDates = $record->items->map(fn ($item) => $item->date?->format('Y-m-d'))->filter()->implode(', ');
                                $totalHours = (string) $record->items->sum('number_of_hours');

                                return [
                                    $record->id,
                                    $record->empNo,
                                    $record->employee?->full_name ?? '—',
                                    $record->employee_group ?? '',
                                    $record->employee?->location?->LocDesc ?? '',
                                    $record->status ?? '',
                                    $otDates,
                                    $totalHours,
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
