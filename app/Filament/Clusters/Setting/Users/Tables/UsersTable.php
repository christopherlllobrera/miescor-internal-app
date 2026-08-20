<?php

namespace App\Filament\Clusters\Setting\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('empNo')
                    ->label('Employee No.')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('username')
                    ->label('Username')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('comp_email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('access_level')
                    ->label('Roles')
                    ->formatStateUsing(fn ($state, $record) => $record->roles->pluck('name')->join(', ')),
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
