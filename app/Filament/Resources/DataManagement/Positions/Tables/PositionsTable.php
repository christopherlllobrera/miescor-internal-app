<?php

namespace App\Filament\Resources\DataManagement\Positions\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PositionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('PostNo')
                    ->label('Position No')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('PostDesc')
                    ->label('Position Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('department.DeptDesc')
                    ->label('Department Name')
                    ->sortable()
                    ->searchable(),
            ])
            ->emptyStateIcon('heroicon-o-document-plus')
            ->emptyStateHeading('No Position yet')
            ->emptyStateDescription('Once you write your first position, it will appear here.')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Add Department')
                    ->url(route('filament.services.resources.positions.create'))
                    ->icon('heroicon-m-plus')
                    ->button(),
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
