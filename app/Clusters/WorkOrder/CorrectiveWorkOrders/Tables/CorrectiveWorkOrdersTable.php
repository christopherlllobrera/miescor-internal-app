<?php

namespace App\Filament\Clusters\WorkOrder\CorrectiveWorkOrders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CorrectiveWorkOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('job_order_no')
                    ->label('Job Order No.')
                    ->sortable()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('plateNo.plate_number')
                    ->label('Vehicle')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($record) => $record->plateNo
                            ? "{$record->plateNo->plate_number} - {$record->plateNo->maker} {$record->plateNo->model}"
                            : 'N/A'
                    )
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('plateNo.vehicle_type')
                    ->label('Vehicle Type')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('driverName.name')
                    ->label('Driver')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('contactPerson.name')
                    ->label('Contact Person')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('contactPerson.contact_number')
                    ->label('Contact No.')
                    ->prefix('+63')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('vehicle_location')
                    ->label('Vehicle Location')
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('odometer_reading')
                    ->label('Odometer')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('requisition_office')
                    ->label('Requisition Office')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
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
