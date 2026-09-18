<?php

namespace App\Filament\Resources\PayrollSelfService\LeaveRequests\Tables;

use App\Models\LeaveRequest;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LeaveRequestsAlternativeTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with(['employee.location', 'immediate_supervisor']))
            ->columns([
                Stack::make([
                    TextColumn::make('name')
                        ->state('Employee:')
                        ->color('gray'),
                    TextColumn::make('employee.full_name')
                        ->label('Employee Name')
                        ->weight(FontWeight::Bold)
                        ->sortable(query: function (Builder $query, string $direction): Builder {
                            $dir = strtolower($direction) === 'desc' ? 'desc' : 'asc';

                            return $query
                                ->join('tblEmployee', 'tblEmployee.EmpNo', '=', 'leave_requests.empNo')
                                ->orderBy('tblEmployee.EmpLName', $dir)
                                ->orderBy('tblEmployee.EmpFName', $dir)
                                ->orderBy('tblEmployee.EmpMName', $dir)
                                ->select('leave_requests.*');
                        })
                        ->searchable(query: function (Builder $query, string $search): Builder {
                            return $query->whereHas('employee', function (Builder $subQuery) use ($search): void {
                                $subQuery->where('EmpLName', 'like', "%{$search}%")
                                    ->orWhere('EmpFName', 'like', "%{$search}%")
                                    ->orWhere('EmpMName', 'like', "%{$search}%")
                                    ->orWhere('EmpNo', 'like', "%{$search}%");
                            });
                        }),

                    TextColumn::make('leave_summary')
                        ->label('Leave Dates')
                        ->color('gray')
                        ->state(function (LeaveRequest $record): string {
                            $type = $record->type ? "{$record->type}: " : '';
                            $start = $record->date_start ? Carbon::parse($record->date_start)->format('M d, Y') : '—';
                            $end = $record->date_end ? Carbon::parse($record->date_end)->format('M d, Y') : '—';
                            $days = $record->days_total ? " ({$record->days_total} days)" : '';

                            if ($start === $end) {
                                return "{$type}{$start}{$days}";
                            }

                            return "{$type}{$start} - {$end}{$days}";
                        }),

                    TextColumn::make('status')
                        ->label('Status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'Approved' => 'success',
                            'Rejected' => 'danger',
                            default => 'warning',
                        })
                        ->icon(fn (string $state): string => match ($state) {
                            'Approved' => 'heroicon-o-check-circle',
                            'Rejected' => 'heroicon-o-x-circle',
                            default => 'heroicon-o-clock',
                        })
                        ->grow(false),
                ])->space(1),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->defaultSort('id', 'desc')
            ->deferLoading()
            ->emptyStateHeading('No Leave Requests found')
            ->emptyStateDescription('Leave Requests will appear here once submitted.')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Pending' => 'Pending',
                        'Approved' => 'Approved',
                        'Rejected' => 'Rejected',
                    ]),
                SelectFilter::make('employee_group')
                    ->label('Employee Group')
                    ->options([
                        'Regular' => 'Regular',
                        'Probationary' => 'Probationary',
                        'Project Hire' => 'Project Hire',
                        'Fixed Term' => 'Fixed Term',
                        'Regular Work Pool' => 'Regular Work Pool',
                        'Service Agreement' => 'Service Agreement',
                        'Meralco Seconded' => 'Meralco Seconded',
                    ]),
                SelectFilter::make('type')
                    ->label('Leave Type')
                    ->options([
                        'Vacation Leave' => 'Vacation Leave',
                        'Sick Leave' => 'Sick Leave',
                        'Emergency Leave' => 'Emergency Leave',
                        'Maternity Leave' => 'Maternity Leave',
                        'Paternity Leave' => 'Paternity Leave',
                        'Solo Parent Leave' => 'Solo Parent Leave',
                        'Bereavement Leave' => 'Bereavement Leave',
                    ]),
                Filter::make('date_range')
                    ->schema([
                        DatePicker::make('from')
                            ->label('Start Date From'),
                        DatePicker::make('until')
                            ->label('Start Date Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn (Builder $q, $date) => $q->whereDate('date_start', '>=', $date))
                            ->when($data['until'] ?? null, fn (Builder $q, $date) => $q->whereDate('date_start', '<=', $date));
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
                Action::make('leave_details')
                    ->label('More Details')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->color('info')
                    ->modalHeading(fn (LeaveRequest $record): string => "Leave Request #{$record->id} — Details (".($record->employee->full_name ?? 'Record').')')
                    ->modalWidth('4xl')
                    ->fillForm(fn (LeaveRequest $record): array => [
                        'employee_name' => $record->employee?->full_name,
                        'leave_type' => $record->type,
                        'duration' => $record->duration,
                        'date_start' => $record->date_start,
                        'date_end' => $record->date_end,
                        'days_total' => $record->days_total,
                        'reason' => $record->reason,
                        'status' => $record->status,
                        'remarks' => $record->remarks,
                        'approver' => $record->immediate_supervisor?->full_name,
                    ])
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('employee_name')
                                ->label('Employee')
                                ->disabled(),
                            TextInput::make('leave_type')
                                ->label('Leave Type')
                                ->disabled(),
                            TextInput::make('status')
                                ->label('Status')
                                ->disabled(),
                        ]),
                        Grid::make(4)->schema([
                            TextInput::make('duration')
                                ->label('Duration')
                                ->disabled(),
                            DatePicker::make('date_start')
                                ->label('Date Start')
                                ->disabled(),
                            DatePicker::make('date_end')
                                ->label('Date End')
                                ->disabled(),
                            TextInput::make('days_total')
                                ->label('Days Total')
                                ->disabled(),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('reason')
                                ->label('Reason')
                                ->disabled(),
                            TextInput::make('approver')
                                ->label('Approver')
                                ->disabled(),
                        ]),
                        TextInput::make('remarks')
                            ->label('Remarks')
                            ->disabled()
                            ->columnSpanFull(),
                    ])
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),

                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->button()
                    ->requiresConfirmation()
                    ->modalHeading('Approve Leave Request')
                    ->modalDescription('Are you sure you want to approve this leave request?')
                    ->visible(fn (LeaveRequest $record): bool => $record->status !== 'Approved')
                    ->action(function (LeaveRequest $record): void {
                        $record->update([
                            'status' => 'Approved',
                            'updated_by' => auth()->id(),
                        ]);

                        Notification::make()
                            ->success()
                            ->title('Leave Request Approved')
                            ->body("Leave Request #{$record->id} was approved.")
                            ->send();
                    }),

                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->button()
                    ->requiresConfirmation()
                    ->modalHeading('Reject Leave Request')
                    ->schema([
                        TextInput::make('remarks')
                            ->label('Rejection Remarks')
                            ->placeholder('Optional reason for rejection'),
                    ])
                    ->visible(fn (LeaveRequest $record): bool => $record->status !== 'Rejected')
                    ->action(function (LeaveRequest $record, array $data): void {
                        $record->update([
                            'status' => 'Rejected',
                            'remarks' => $data['remarks'] ?? $record->remarks,
                            'updated_by' => auth()->id(),
                        ]);

                        Notification::make()
                            ->danger()
                            ->title('Leave Request Rejected')
                            ->body("Leave Request #{$record->id} was rejected.")
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
