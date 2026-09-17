<?php

namespace App\Filament\Resources\ArchivedContracts\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Models\Contract;
use Filament\Actions\ViewAction;

class ArchivedContractsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference_no')
                    ->label('Reference No')
                    ->searchable(),
                TextColumn::make('contract_title')
                    ->label('Contract Title')
                    ->searchable(),
                TextColumn::make('proponent')
                    ->label('Contract Proponent')
                    ->searchable(),
                TextColumn::make('assignees.EmpLName')
                    ->label('Assigned To')
                    ->formatStateUsing(fn (string $state): string => 'Atty. '.$state)
                    ->badge()
                    ->color('info')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color('success'),
                TextColumn::make('updated_at')
                    ->label('Archived At')
                    ->dateTime('M j, Y H:i')
                    ->timezone('Asia/Manila')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('viewDocument')
                    ->label('View Document')
                    ->icon('heroicon-o-link')
                    ->color('success')
                    ->url(fn (Contract $record): ?string => $record->attachment)
                    ->openUrlInNewTab()
                    ->visible(fn (Contract $record): bool => filled($record->attachment)),
                ViewAction::make(),
            ])
            ->toolbarActions([
            ]);
    }
}
