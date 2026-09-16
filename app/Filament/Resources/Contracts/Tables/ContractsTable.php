<?php

namespace App\Filament\Resources\Contracts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContractsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference_no')
                    ->label('Reference No')
                    ->searchable(),
                TextColumn::make('contract_description')
                    ->label('Contract Description')
                    ->searchable(),
                TextColumn::make('proponent')
                    ->label('Contract Proponent')
                    ->searchable(),
                TextColumn::make('assigned_to')
                    ->label('Assigned To')
                    ->formatStateUsing(fn (?string $state): string => filled($state) ? 'Atty. '.$state : 'Unassigned')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'proponent-pending' => 'gray',
                        'in-progress' => 'info',
                        'for-approval' => 'primary',
                        'for-execution' => 'info',
                        'executed' => 'success',
                        'due' => 'danger',
                        default => 'primary',
                    }),
                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('M j, H:i')
                    ->timezone('Asia/Manila')
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
