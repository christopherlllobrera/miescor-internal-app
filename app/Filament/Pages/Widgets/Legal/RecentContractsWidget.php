<?php

namespace App\Filament\Pages\Widgets\Legal;

use App\Models\Contract;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentContractsWidget extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Contract::query()->latest()->limit(5))
            ->columns([
                TextColumn::make('reference_no')
                    ->label('Reference No')
                    ->searchable(),
                TextColumn::make('proponent')
                    ->label('Contract Proponent')
                    ->searchable(),
                TextColumn::make('assigned_to')
                    ->label('Assigned To')
                    ->formatStateUsing(fn (?string $state): string => filled($state) ? 'Atty. '.$state : 'Unassigned')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'violet' => 'pending',
                        'info' => 'in-progress',
                        'warning' => 'for-approval',
                        'success' => 'completed',
                        'danger' => 'due',
                    ]),
                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->timezone('Asia/Manila')
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
