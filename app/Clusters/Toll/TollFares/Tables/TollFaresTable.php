<?php

namespace App\Filament\Clusters\Toll\TollFares\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TollFaresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tollRoad.name')
                    ->label('Toll Road')
                    ->sortable(),

                TextColumn::make('entryPoint.name')
                    ->label('Entry Point')
                    ->sortable(),

                TextColumn::make('exitPoint.name')
                    ->label('Exit Point')
                    ->sortable(),

                TextColumn::make('fare_class_1')
                    ->label('Class 1 (Car)')
                    ->money('PHP')
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
