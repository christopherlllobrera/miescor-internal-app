<?php

namespace App\Filament\Resources\AttendanceAuthorizationForms\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttendanceAuthorizationFormsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Form #')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Total Entries')
                    ->sortable(),
                TextColumn::make('immediate_supervisor.full_name')
                    ->label('Approver / Supervisor')
                    ->searchable(['EmpLName', 'EmpFName'])
                    ->sortable(),
                TextColumn::make('reason')
                    ->label('Reason')
                    ->limit(40)
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        default => 'warning',
                    }),
                TextColumn::make('created_at')
                    ->label('Date Filed')
                    ->dateTime()
                    ->sortable(),
            ])
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
