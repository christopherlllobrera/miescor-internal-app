<?php

namespace App\Filament\Pages\Widgets\Legal;

use App\Models\Contract;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
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
                TextColumn::make('contract_description')
                    ->label('Contract Description')
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
                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('M j, H:i')
                    ->timezone('Asia/Manila')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'proponent-pending' => 'Pending with Proponent',
                        'in-progress' => 'In-Progress',
                        'for-approval' => 'For Approval',
                        'for-execution' => 'For Execution',
                        'executed' => 'Executed',
                        'due' => 'Due',
                    ]),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->paginated(false);
    }
}
