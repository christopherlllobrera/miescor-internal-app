<?php

namespace App\Filament\Resources\Fleet\IncidentManagement\Tables;

use App\Filament\Resources\Fleet\IncidentManagement\IncidentManagementResource;
use App\Models\IncidentManagement;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class IncidentManagementTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('priority')
                    ->label('Priority')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Low' => 'success',
                        'Medium' => 'warning',
                        'High' => 'danger',
                    }),
                TextColumn::make('incident_type')
                    ->label('Incident Type')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('plate_number')
                    ->label('Vehicle')
                    ->searchable(),
                TextColumn::make('company_no')
                    ->label('Company No.')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('reported_by')
                    ->label('Reported By')
                    ->searchable(),
                TextColumn::make('reported_date')
                    ->label('Reported Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Open' => 'info',
                        'Pending' => 'warning',
                        'Resolved' => 'success',
                    }),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('Resolved')
                    ->label('Resolved')
                    ->color('success')
                    ->icon('heroicon-o-cursor-arrow-ripple')
                    ->url(fn (IncidentManagement $record): string => IncidentManagementResource::getUrl('approve', ['record' => $record])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
