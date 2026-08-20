<?php

namespace App\Filament\Clusters\Setting\Roles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('permissions.name')
                    ->listWithLineBreaks(),
            ])
            ->paginated([10, 25, 50, 100])
            ->emptyStateHeading('No Role yet')
            ->emptyStateDescription('Once you create your first role, it will appear here.')
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
