<?php

namespace App\Filament\Resources\Contracts\Pages;

use App\Filament\Resources\Contracts\ContractResource;
use App\Models\Contract;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Relaticle\Flowforge\Board;
use Relaticle\Flowforge\BoardResourcePage;
use Relaticle\Flowforge\Column;
use Relaticle\Flowforge\Components\CardFlex;

class ContractBoard extends BoardResourcePage
{
    protected static string $resource = ContractResource::class;

    protected static ?string $title = 'Contracts Board';

    public function board(Board $board): Board
    {
        return $board
            ->query($this->getEloquentQuery())
            ->recordTitleAttribute('reference_no')
            ->columnIdentifier('status')
            ->positionIdentifier('position')
            ->columns([
                Column::make('pending')->label('Pending')->icon('heroicon-o-inbox')->color('violet'),
                Column::make('in-progress')->label('In Progress')->icon('heroicon-o-arrow-path')->color('info'),
                Column::make('for-approval')->label('For Approval')->icon('heroicon-o-eye')->color('primary'),
                Column::make('completed')->label('Completed')->icon('heroicon-o-check-circle')->color('success'),
                Column::make('due')->label('Due')->icon('heroicon-o-exclamation-circle')->color('danger'),
            ])
            ->recordActions([
                EditAction::make()
                    ->url(fn (Contract $record): string => ContractResource::getUrl('edit', ['record' => $record])),
            ])
            ->cardSchema(fn (Schema $schema): Schema => $schema
                ->components([
                    TextEntry::make('contract_type')
                        ->hiddenLabel()
                        ->badge()
                        ->size('lg')
                        ->color('primary'),
                    TextEntry::make('assigned_to')
                        ->hiddenLabel()
                        ->icon('heroicon-o-user')
                        ->color('gray')
                        ->placeholder('Unassigned')
                        ->visible(fn (Contract $record): bool => $record->status !== 'pending')
                        ->formatStateUsing(function (?string $state): ?string {
                            if (! $state) {
                                return null;
                            }

                            if (str_contains($state, ',')) {
                                $parts = explode(',', $state);

                                return 'Atty. '.trim($parts[0]);
                            }

                            if (str_starts_with($state, 'Atty.')) {
                                $parts = explode(' ', $state);

                                return 'Atty. '.end($parts);
                            }

                            return 'Atty. '.$state;
                        }),
                    CardFlex::make([
                        TextEntry::make('turnaround_date')
                            ->hiddenLabel()
                            ->date('M j, Y')
                            ->icon('heroicon-o-calendar')
                            ->color('info')
                            ->size('xs')
                            ->extraAttributes(['class' => 'flex items-center'])
                            ->visible(fn (Contract $record): bool => $record->has_turnaround_time && $record->turnaround_date !== null && $record->deadline === null),
                        TextEntry::make('deadline')
                            ->hiddenLabel()
                            ->date('M j, Y')
                            ->icon('heroicon-o-calendar')
                            ->color('danger')
                            ->size('xs')
                            ->extraAttributes(['class' => 'flex items-center'])
                            ->visible(fn (Contract $record): bool => $record->deadline !== null),
                        TextEntry::make('attachment_count')
                            ->hiddenLabel()
                            ->state(fn (Contract $record): int => filled($record->attachment) ? 1 : 0)
                            ->icon('heroicon-o-paper-clip')
                            ->color('gray')
                            ->size('xs')
                            ->extraAttributes(['class' => 'border-s border-gray-200 ps-2 dark:border-gray-700']),
                        TextEntry::make('remarks_count')
                            ->hiddenLabel()
                            ->state(fn (Contract $record): int => filled($record->remarks) ? 1 : 0)
                            ->icon('heroicon-o-chat-bubble-bottom-center-text')
                            ->color('gray')
                            ->size('xs'),
                    ])
                        ->wrap(false),
                ]));
    }

    public function getEloquentQuery(): Builder
    {
        return Contract::query();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label('New Contract')
                ->url(fn (): string => ContractResource::getUrl('create')),

            Action::make('confirmMove')
                ->extraAttributes(['style' => 'display: none;'])
                ->requiresConfirmation()
                ->modalHeading('Move to For Approval')
                ->modalDescription('Are you sure you want to move this contract to For Approval? Make sure all necessary information is attached.')
                ->modalSubmitActionLabel('Yes, move it')
                ->action(function (array $arguments) {
                    $this->forceMoveCard(
                        $arguments['cardId'],
                        $arguments['targetColumnId'],
                        $arguments['afterCardId'] ?? null,
                        $arguments['beforeCardId'] ?? null
                    );
                }),
            Action::make('confirmMoveToCompleted')
                ->extraAttributes(['style' => 'display: none;'])
                ->requiresConfirmation()
                ->modalHeading('Move to Completed')
                ->modalDescription('Are you sure you want to mark this contract as Completed? This action cannot be undone easily.')
                ->modalSubmitActionLabel('Yes, complete it')
                ->action(function (array $arguments) {
                    $this->forceMoveCard(
                        $arguments['cardId'],
                        $arguments['targetColumnId'],
                        $arguments['afterCardId'] ?? null,
                        $arguments['beforeCardId'] ?? null
                    );
                }),
        ];
    }

    public function moveCard(string $cardId, string $targetColumnId, ?string $afterCardId = null, ?string $beforeCardId = null): void
    {
        $contract = Contract::find($cardId);

        if ($targetColumnId !== 'pending' && blank($contract?->assigned_to)) {
            Notification::make()
                ->warning()
                ->title('The contract is not assigned to anyone yet.')
                ->send();

            return;
        }

        if ($contract->status === 'in-progress' && $targetColumnId === 'for-approval') {
            $this->mountAction('confirmMove', [
                'cardId' => $cardId,
                'targetColumnId' => $targetColumnId,
                'afterCardId' => $afterCardId,
                'beforeCardId' => $beforeCardId,
            ]);

            $this->dispatch('kanban-board-refresh');

            return;
        }

        if ($contract->status === 'for-approval' && $targetColumnId === 'completed') {
            $this->mountAction('confirmMoveToCompleted', [
                'cardId' => $cardId,
                'targetColumnId' => $targetColumnId,
                'afterCardId' => $afterCardId,
                'beforeCardId' => $beforeCardId,
            ]);

            $this->dispatch('kanban-board-refresh');

            return;
        }

        parent::moveCard($cardId, $targetColumnId, $afterCardId, $beforeCardId);
    }

    public function forceMoveCard(string $cardId, string $targetColumnId, ?string $afterCardId = null, ?string $beforeCardId = null): void
    {
        parent::moveCard($cardId, $targetColumnId, $afterCardId, $beforeCardId);
    }
}
