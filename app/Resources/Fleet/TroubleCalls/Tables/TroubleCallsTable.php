<?php

namespace App\Filament\Resources\Fleet\TroubleCalls\Tables;

use App\Filament\Resources\Fleet\TroubleCalls\TroubleCallResource;
use App\Models\TroubleCall;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TroubleCallsTable
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
                TextColumn::make('company_no')
                    ->label('Company No.')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('trouble_call_no')
                    ->label('Trouble Call No.')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('plate_number')
                    ->label('Vehicle')
                    ->searchable(),
                TextColumn::make('reported_by')
                    ->label('Reported By')
                    ->searchable(),
                TextColumn::make('reported_date')
                    ->label('Reported Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('location')
                    ->label('Location')
                    ->searchable(),
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
                    ->url(fn (TroubleCall $record): string => TroubleCallResource::getUrl('approve', ['record' => $record])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
