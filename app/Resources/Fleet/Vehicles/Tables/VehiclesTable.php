<?php

namespace App\Filament\Resources\Fleet\Vehicles\Tables;

use App\Models\Vehicles;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VehiclesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('group')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Internal' => 'info',
                        'External' => 'warning',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('company_no')
                    ->label('Company No.')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('driver')
                    ->label('Operator')
                    ->default('Unassigned')
                    ->formatStateUsing(fn ($state) => $state ?: 'Unassigned')
                    ->badge()
                    ->color(fn ($state): string => $state === 'Unassigned' ? 'gray' : 'success')
                    ->sortable(),
                TextColumn::make('plate_number')
                    ->label('Plate Number')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('model')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('maker')
                    ->searchable(),
                TextColumn::make('vehicle_type')
                    ->label('Vehicle Type')
                    ->searchable(),
                TextColumn::make('fuel_type')
                    ->label('Fuel Type')
                    ->searchable(),
                TextColumn::make('vehicle_category')
                    ->sortable()
                    ->label('Vehicle Category')
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Vehicles::STATUS_AVAILABLE => 'success',
                        Vehicles::STATUS_IN_USE => 'warning',
                        Vehicles::STATUS_MAINTENANCE => 'info',
                        Vehicles::STATUS_OUT_OF_SERVICE => 'danger',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
