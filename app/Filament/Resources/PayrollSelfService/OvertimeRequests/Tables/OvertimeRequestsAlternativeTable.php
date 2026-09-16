<?php

namespace App\Filament\Resources\PayrollSelfService\OvertimeRequests\Tables;

use App\Models\OvertimeRequest;
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

class OvertimeRequestsAlternativeTable
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
                                ->join('tblEmployee', 'tblEmployee.EmpNo', '=', 'overtime_requests.empNo')
                                ->orderBy('tblEmployee.EmpLName', $direction)
                                ->orderBy('tblEmployee.EmpFName', $direction)
                                ->orderBy('tblEmployee.EmpMName', $direction)
                                ->select('overtime_requests.*');
                        })
                        ->searchable(query: function (Builder $query, string $search): Builder {
                            return $query->whereHas('employee', function (Builder $subQuery) use ($search): void {
                                $subQuery->where('EmpLName', 'like', "%{$search}%")
                                    ->orWhere('EmpFName', 'like', "%{$search}%")
                                    ->orWhere('EmpMName', 'like', "%{$search}%")
                                    ->orWhere('EmpNo', 'like', "%{$search}%");
                            });
                        }),

                    TextColumn::make('ot_dates')
                        ->label('OT Dates')
                        ->color('gray')
                        ->state(function (OvertimeRequest $record): string {
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
                        ->tooltip(fn (OvertimeRequest $record): ?string => $record->items->map(fn ($item) => $item->date?->format('Y-m-d'))->filter()->implode(', ')),

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
            ->emptyStateHeading('No Overtime Requests found')
            ->emptyStateDescription('Overtime Requests will appear here once submitted.')
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
                Action::make('ot_items')
                    ->label('More Details')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->color('info')
                    ->modalHeading(fn (OvertimeRequest $record): string => "Overtime Request #{$record->id} — Items Details (".($record->employee?->full_name ?? 'Record').')')
                    ->modalWidth('4xl')
                    ->fillForm(fn (OvertimeRequest $record): array => [
                        'employee_name' => $record->employee?->full_name,
                        'schedule' => $record->schedule,
                        'status' => $record->status,
                        'items' => $record->items->map(fn ($item): array => [
                            'date' => $item->date?->format('Y-m-d'),
                            'ot_start' => $item->ot_start,
                            'ot_end' => $item->ot_end,
                            'number_of_hours' => $item->number_of_hours,
                            'reason' => $item->reason,
                        ])->toArray(),
                    ])
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('employee_name')
                                ->label('Employee')
                                ->disabled(),
                            TextInput::make('schedule')
                                ->label('Schedule')
                                ->disabled(),
                            TextInput::make('status')
                                ->label('Status')
                                ->disabled(),
                        ]),
                        Repeater::make('items')
                            ->label('Overtime Request Items')
                            ->columns(5)
                            ->schema([
                                DatePicker::make('date')->label('Date')->disabled(),
                                TimePicker::make('ot_start')->label('OT Start')->disabled(),
                                TimePicker::make('ot_end')->label('OT End')->disabled(),
                                TextInput::make('number_of_hours')->label('Hours')->disabled(),
                                TextInput::make('reason')->label('Reason')->disabled(),
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
                    ->modalHeading('Approve Overtime Request')
                    ->modalDescription('Are you sure you want to approve this overtime request?')
                    ->visible(fn (OvertimeRequest $record): bool => $record->status !== 'Approved')
                    ->action(function (OvertimeRequest $record): void {
                        $record->update([
                            'status' => 'Approved',
                            'updated_by' => auth()->id(),
                        ]);

                        Notification::make()
                            ->success()
                            ->title('Overtime Request Approved')
                            ->body("Overtime Request #{$record->id} was approved.")
                            ->send();
                    }),

                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->button()
                    ->requiresConfirmation()
                    ->modalHeading('Reject Overtime Request')
                    ->schema([
                        TextInput::make('remarks')
                            ->label('Rejection Remarks')
                            ->placeholder('Optional reason for rejection'),
                    ])
                    ->visible(fn (OvertimeRequest $record): bool => $record->status !== 'Rejected')
                    ->action(function (OvertimeRequest $record, array $data): void {
                        $record->update([
                            'status' => 'Rejected',
                            'remarks' => $data['remarks'] ?? $record->remarks,
                            'updated_by' => auth()->id(),
                        ]);

                        Notification::make()
                            ->danger()
                            ->title('Overtime Request Rejected')
                            ->body("Overtime Request #{$record->id} was rejected.")
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
