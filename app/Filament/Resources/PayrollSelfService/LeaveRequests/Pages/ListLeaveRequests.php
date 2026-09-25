<?php

namespace App\Filament\Resources\PayrollSelfService\LeaveRequests\Pages;

use App\Filament\Resources\PayrollSelfService\Exports\LeaveRequestExporter;
use App\Filament\Resources\PayrollSelfService\LeaveRequests\LeaveRequestResource;
use App\Models\LeaveRequest;
use App\Services\TextExportService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\ExportAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListLeaveRequests extends ListRecords
{
    protected static string $resource = LeaveRequestResource::class;

    protected static ?string $title = 'Leave Request';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create_sick_leave')
                ->label('Sick Leave')
                ->icon('heroicon-o-plus')
                ->color('info')
                ->url(fn (): string => LeaveRequestResource::getUrl('create', ['type' => 'Sick Leave'])),
            Action::make('create_vacation_leave')
                ->label('Vacation Leave')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->url(fn (): string => LeaveRequestResource::getUrl('create', ['type' => 'Vacation Leave'])),
            Action::make('switch_detailed_view')
                ->label('Review')
                ->icon('heroicon-o-view-columns')
                ->color('gray')
                ->url(fn (): string => LeaveRequestResource::getUrl('detailed')),
            ActionGroup::make([
                ExportAction::make('export_excel_csv')
                    ->label('Export (Excel / CSV)')
                    ->icon('heroicon-o-table-cells')
                    ->exporter(LeaveRequestExporter::class)
                    ->columnMappingColumns(2)
                    ->modifyQueryUsing(function (Builder $query, array $options): Builder {
                        return $query
                            ->when($options['start_date'] ?? null, fn (Builder $q, $date) => $q->whereDate('date_start', '>=', $date))
                            ->when($options['end_date'] ?? null, fn (Builder $q, $date) => $q->whereDate('date_end', '<=', $date));
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
                        $query = LeaveRequest::query()
                            ->with(['employee', 'location'])
                            ->when($data['start_date'] ?? null, fn (Builder $q, $date) => $q->whereDate('date_start', '>=', $date))
                            ->when($data['end_date'] ?? null, fn (Builder $q, $date) => $q->whereDate('date_end', '<=', $date))
                            ->latest('id');

                        $headers = [
                            'ID',
                            'Employee No',
                            'Employee Name',
                            'Employee Group',
                            'Sub Area',
                            'Schedule',
                            'Leave Type',
                            'Start Date',
                            'End Date',
                            'Days Total',
                            'Duration',
                            'Reason',
                            'Status',
                            'Remarks',
                            'Date Filed',
                        ];

                        $filename = 'leave_requests_'.now()->format('Ymd_His').'.txt';

                        return TextExportService::streamQuery($filename, $headers, $query, function (LeaveRequest $record): array {
                            return [
                                $record->id,
                                $record->empNo,
                                $record->employee?->full_name ?? '—',
                                $record->employee_group ?? '',
                                $record->location?->LocDesc ?? '',
                                $record->schedule ?? '',
                                $record->type ?? '',
                                $record->date_start ?? '',
                                $record->date_end ?? '',
                                $record->days_total ?? '',
                                $record->duration ?? '',
                                $record->reason ?? '',
                                $record->status ?? '',
                                $record->remarks ?? '',
                                $record->created_at?->format('Y-m-d H:i:s') ?? '',
                            ];
                        }, $data['delimiter'] ?? 'tab');
                    }),
            ])
                ->label('Export')
                ->color('gray')
                ->icon('heroicon-m-arrow-down-tray')
                ->button(),
        ];
    }
}
