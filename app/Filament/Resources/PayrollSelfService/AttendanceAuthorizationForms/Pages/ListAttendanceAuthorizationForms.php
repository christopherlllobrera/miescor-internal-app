<?php

namespace App\Filament\Resources\PayrollSelfService\AttendanceAuthorizationForms\Pages;

use App\Filament\Resources\PayrollSelfService\AttendanceAuthorizationForms\AttendanceAuthorizationFormResource;
use App\Filament\Resources\PayrollSelfService\Exports\AttendanceAuthExporter;
use App\Models\AttendanceAuth;
use App\Services\TextExportService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListAttendanceAuthorizationForms extends ListRecords
{
    protected static string $resource = AttendanceAuthorizationFormResource::class;

    protected static ?string $title = 'Attendance Authorization';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Create'),
            Action::make('switch_detailed_view')
                ->label('Review')
                ->icon('heroicon-o-view-columns')
                ->color('gray')
                ->url(fn (): string => AttendanceAuthorizationFormResource::getUrl('detailed')),
            ActionGroup::make([
                ExportAction::make('export_excel_csv')
                    ->label('Export to Excel / CSV')
                    ->icon('heroicon-o-table-cells')
                    ->exporter(AttendanceAuthExporter::class)
                    ->columnMappingColumns(2)
                    ->modifyQueryUsing(function (Builder $query, array $options): Builder {
                        return $query
                            ->when($options['start_date'] ?? null, function (Builder $q, $date) {
                                $q->where(function (Builder $sub) use ($date) {
                                    $sub->whereDate('created_at', '>=', $date)
                                        ->orWhereHas('items', fn ($itemQ) => $itemQ->whereDate('date', '>=', $date));
                                });
                            })
                            ->when($options['end_date'] ?? null, function (Builder $q, $date) {
                                $q->where(function (Builder $sub) use ($date) {
                                    $sub->whereDate('created_at', '<=', $date)
                                        ->orWhereHas('items', fn ($itemQ) => $itemQ->whereDate('date', '<=', $date));
                                });
                            });
                    }),
                Action::make('export_text')
                    ->label('Export as Text (.txt)')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        DatePicker::make('start_date')
                            ->label('From Date'),
                        DatePicker::make('end_date')
                            ->label('Until Date'),
                        Select::make('delimiter')
                            ->label('Format / Delimiter')
                            ->options([
                                'tab' => 'Tab-delimited (.txt)',
                                'comma' => 'Comma-delimited (.txt)',
                                'pipe' => 'Pipe-delimited (.txt)',
                            ])
                            ->default('tab')
                            ->required(),
                    ])
                    ->action(function (array $data): StreamedResponse {
                        $query = AttendanceAuth::query()
                            ->with(['employee.location', 'items'])
                            ->when($data['start_date'] ?? null, function (Builder $q, $date) {
                                $q->where(function (Builder $sub) use ($date) {
                                    $sub->whereDate('created_at', '>=', $date)
                                        ->orWhereHas('items', fn ($itemQ) => $itemQ->whereDate('date', '>=', $date));
                                });
                            })
                            ->when($data['end_date'] ?? null, function (Builder $q, $date) {
                                $q->where(function (Builder $sub) use ($date) {
                                    $sub->whereDate('created_at', '<=', $date)
                                        ->orWhereHas('items', fn ($itemQ) => $itemQ->whereDate('date', '<=', $date));
                                });
                            })
                            ->latest('id');

                        $headers = [
                            'ID',
                            'Employee No',
                            'Employee Name',
                            'Employee Group',
                            'Sub Area',
                            'Schedule',
                            'Reason',
                            'Status',
                            'AAF Dates',
                            'Remarks',
                            'Date Filed',
                        ];

                        $filename = 'attendance_auth_'.now()->format('Ymd_His').'.txt';

                        return TextExportService::streamQuery($filename, $headers, $query, function (AttendanceAuth $record): array {
                            $aafDates = $record->items->map(fn ($item) => $item->date?->format('Y-m-d'))->filter()->implode(', ');

                            return [
                                $record->id,
                                $record->empNo,
                                $record->employee?->full_name ?? '—',
                                $record->employee_group ?? '',
                                $record->employee?->location?->LocDesc ?? '',
                                $record->schedule ?? '',
                                $record->items->pluck('reason')->filter()->unique()->implode(', '),
                                $record->status ?? '',
                                $aafDates,
                                $record->remarks ?? '',
                                $record->created_at?->format('Y-m-d H:i:s') ?? '',
                            ];
                        }, $data['delimiter'] ?? 'tab');
                    }),
            ])
                ->label('Export')
                ->icon('heroicon-m-arrow-down-tray')
                ->color('gray')
                ->button(),
        ];
    }
}
