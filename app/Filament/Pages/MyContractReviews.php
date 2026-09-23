<?php

namespace App\Filament\Pages;

use App\Models\Contract;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class MyContractReviews extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|UnitEnum|null $navigationGroup = 'Contract Management';

    protected static ?string $navigationLabel = 'My Contract Reviews';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.my-contract-reviews';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Contract::whereHas('reviewers', function (Builder $query) {
                    $query->where('user_id', auth()->id())
                        ->where('status', 'pending');
                })
            )
            ->columns([
                TextColumn::make('reference_no')->searchable(),
                TextColumn::make('contract_title')->searchable(),
                TextColumn::make('attachment')
                    ->label('Document Link')
                    ->limit(30)
                    ->url(fn (Contract $record): ?string => $record->attachment)
                    ->openUrlInNewTab()
                    ->color('primary'),
                TextColumn::make('status'),
            ])
            ->actions([
                Action::make('view_document')
                    ->label('View in SharePoint')
                    ->icon('heroicon-o-link')
                    ->url(fn (Contract $record): ?string => $record->attachment)
                    ->openUrlInNewTab()
                    ->visible(fn (Contract $record): bool => (bool) $record->attachment),
                Action::make('mark_reviewed')
                    ->label('Mark as Reviewed')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Contract $record) {
                        $record->reviewers()->updateExistingPivot(auth()->id(), [
                            'status' => 'reviewed',
                            'reviewed_at' => now(),
                        ]);
                        Notification::make()
                            ->title('Contract marked as reviewed')
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
