<?php

namespace App\Filament\Resources\PayrollSelfService\AttendanceAuthorizationForms\Tables;

use App\Models\AttendanceAuth;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AttendanceAuthorizationFormsAlternativeTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with(['employee.location', 'immediate_supervisor', 'items']))
            ->columns([
                Stack::make([
                    TextColumn::make('name')
                        ->state('Employee:')
                        ->color('gray'),
                    TextColumn::make('employee.full_name')
                        ->label('Employee Name')
                        ->weight(FontWeight::Bold)
                        ->sortable(query: function (Builder $query, string $direction): Builder {
                            return $query
                                ->join('tblEmployee', 'tblEmployee.EmpNo', '=', 'attendance_auths.empNo')
                                ->orderBy('tblEmployee.EmpLName', $direction)
                                ->orderBy('tblEmployee.EmpFName', $direction)
                                ->orderBy('tblEmployee.EmpMName', $direction)
                                ->select('attendance_auths.*');
                        })
                        ->searchable(query: function (Builder $query, string $search): Builder {
                            return $query->whereHas('employee', function (Builder $subQuery) use ($search): void {
                                $subQuery->where('EmpLName', 'like', "%{$search}%")
                                    ->orWhere('EmpFName', 'like', "%{$search}%")
                                    ->orWhere('EmpMName', 'like', "%{$search}%")
                                    ->orWhere('EmpNo', 'like', "%{$search}%");
                            });
                        }),

                    TextColumn::make('aaf_dates')
                        ->label('AAF Dates')
                        ->color('gray')
                        ->state(function (AttendanceAuth $record): string {
                            $items = $record->items;
                            if ($items->isEmpty()) {
                                return 'No dates specified';
                            }

                            $dates = $items->map(fn ($item) => $item->date?->format('M d, Y'))->filter()->values();

                            if ($dates->count() === 1) {
                                return "Date: {$dates->first()}";
                            }

                            return "Dates: {$dates->first()} (+".($dates->count() - 1).' more)';
                        })
                        ->tooltip(fn (AttendanceAuth $record): ?string => $record->items->map(fn ($item) => $item->date?->format('Y-m-d'))->filter()->implode(', ')),

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
            ->emptyStateHeading('No Attendance Authorization Forms found')
            ->emptyStateDescription('Attendance Authorization Forms will appear here once submitted.')
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
                SelectFilter::make('reason')
                    ->label('Reason')
                    ->options([
                        'TCD Malfunction' => 'TCD Malfunction',
                        'Forgot to Log in or Log out' => 'Forgot to Log in or Log out',
                        'Out of Base for Official Business' => 'Out of Base for Official Business',
                        'No Company ID' => 'No Company ID',
                    ]),
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
                Action::make('aaf_items')
                    ->label('More Details')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->color('info')
                    // ->button()
                    ->modalHeading(fn (AttendanceAuth $record): string => "AAF #{$record->id} — Items Details (".($record->employee?->full_name ?? 'Record').')')
                    ->modalWidth('4xl')
                    ->fillForm(fn (AttendanceAuth $record): array => [
                        'employee_name' => $record->employee?->full_name,
                        'reason' => $record->reason,
                        'status' => $record->status,
                        'items' => $record->items->map(fn ($item): array => [
                            'date' => $item->date?->format('Y-m-d'),
                            'time_in' => $item->time_in,
                            'request_time_in' => $item->request_time_in,
                            'time_out' => $item->time_out,
                            'request_time_out' => $item->request_time_out,
                        ])->toArray(),
                    ])
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('employee_name')
                                ->label('Employee')
                                ->disabled(),
                            TextInput::make('reason')
                                ->label('Reason')
                                ->disabled(),
                            TextInput::make('status')
                                ->label('Status')
                                ->disabled(),
                        ]),
                        Repeater::make('items')
                            ->label('Attendance Authorization Dates & Times')
                            ->columns(5)
                            ->schema([
                                DatePicker::make('date')->label('Date')->disabled(),
                                TimePicker::make('time_in')->label('Time In')->disabled(),
                                TimePicker::make('request_time_in')->label('Request Time In')->disabled(),
                                TimePicker::make('time_out')->label('Time Out')->disabled(),
                                TimePicker::make('request_time_out')->label('Request Time Out')->disabled(),
                            ])
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false),
                    ])
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),

                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->button()
                    ->requiresConfirmation()
                    ->modalHeading('Approve Attendance Authorization')
                    ->modalDescription('Are you sure you want to approve this attendance authorization form?')
                    ->visible(fn (AttendanceAuth $record): bool => $record->status !== 'Approved')
                    ->action(function (AttendanceAuth $record): void {
                        $record->update([
                            'status' => 'Approved',
                            'updated_by' => auth()->id(),
                        ]);

                        Notification::make()
                            ->success()
                            ->title('AAF Approved')
                            ->body("Attendance Authorization #{$record->id} was approved.")
                            ->send();
                    }),

                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->button()
                    ->requiresConfirmation()
                    ->modalHeading('Reject Attendance Authorization')
                    ->schema([
                        TextInput::make('remarks')
                            ->label('Rejection Remarks')
                            ->placeholder('Optional reason for rejection'),
                    ])
                    ->visible(fn (AttendanceAuth $record): bool => $record->status !== 'Rejected')
                    ->action(function (AttendanceAuth $record, array $data): void {
                        $record->update([
                            'status' => 'Rejected',
                            'remarks' => $data['remarks'] ?? $record->remarks,
                            'updated_by' => auth()->id(),
                        ]);

                        Notification::make()
                            ->danger()
                            ->title('AAF Rejected')
                            ->body("Attendance Authorization #{$record->id} was rejected.")
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
