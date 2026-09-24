<?php

namespace App\Filament\Resources\ContractManagement\Contracts\Tables;

use App\Models\Contract;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
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
                TextColumn::make('review_progress')
                    ->label('Review Progress')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'No reviewers assigned' ? 'gray' : (str_contains($state, '0/') ? 'warning' : 'success')),
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
                Action::make('viewDocument')
                    ->label('View Document')
                    ->icon('heroicon-o-link')
                    ->color('success')
                    ->url(fn (Contract $record): ?string => $record->attachment)
                    ->openUrlInNewTab()
                    ->visible(fn (Contract $record): bool => filled($record->attachment)),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
