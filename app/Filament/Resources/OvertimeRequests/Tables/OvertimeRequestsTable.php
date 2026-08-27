<?php

namespace App\Filament\Resources\OvertimeRequests\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;

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
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
