<?php

namespace App\Filament\Resources\Contracts\Pages;

use App\Filament\Resources\Contracts\ContractResource;
use App\Models\Contract;
use App\Models\Employee;
use App\Notifications\ContractStatusUpdated;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
        $columns = [];
        $user = auth()->user();

        if ($user->can('view-kanban-board-all')) {
            $columns[] = Column::make('pending')->label('Pending')->icon('heroicon-o-inbox')->color('violet');
            $columns[] = Column::make('proponent-pending')->label('Pending with Proponent')->icon('heroicon-o-eye')->color('violet');
        }

        if ($user->can('view-kanban-board-all') || $user->can('view-kanban-board-in-progress')) {
            $columns[] = Column::make('in-progress')->label('In Progress')->icon('heroicon-o-arrow-path')->color('info');
        }

        if ($user->can('view-kanban-board-all') || $user->can('view-kanban-board-for-approval')) {
            $columns[] = Column::make('for-approval')->label('For Approval')->icon('heroicon-o-eye')->color('primary');
        }

        if ($user->can('view-kanban-board-all')) {
            $columns[] = Column::make('for-execution')->label('For Execution')->icon('heroicon-o-printer')->color('info');
        }
        // Change to executed
        if ($user->can('view-kanban-board-all')) {
            $columns[] = Column::make('executed')->label('Executed')->icon('heroicon-o-check-circle')->color('success');
        }

        if ($user->can('view-kanban-board-all') || $user->can('view-kanban-board-due')) {
            $columns[] = Column::make('due')->label('Due')->icon('heroicon-o-exclamation-circle')->color('danger');
        }

        return $board
            ->query($this->getEloquentQuery())
            ->recordTitleAttribute('reference_no')
            ->columnIdentifier('status')
            ->positionIdentifier('position')
            ->columns($columns)
            ->recordActions([
                EditAction::make()
                    ->url(fn (Contract $record): string => ContractResource::getUrl('edit', ['record' => $record]))
                    ->visible(fn (Contract $record): bool => ! in_array($record->status, ['pending', 'proponent-pending'])),
                Action::make('assign')
                    ->label('Assign')
                    ->icon('heroicon-o-user-plus')
                    ->visible(fn (Contract $record): bool => in_array($record->status, ['pending', 'proponent-pending']))
                    ->form([
                        Select::make('assignee_ids')
                            ->label('Assign To')
                            ->options(fn () => Employee::whereHas('position', function ($q) {
                                $q->where('PostDesc', 'Legal Counsel');
                            })->get()->mapWithKeys(fn ($record) => [
                                $record->EmpNo => $record->full_name ?? "{$record->EmpLName}, {$record->EmpFName}",
                            ]))
                            ->default(fn (Contract $record) => $record->assignees->pluck('EmpNo')->toArray())
                            ->preload()
                            ->multiple()
                            ->searchable()
                            ->suffixAction(
                                Action::make('clear')
                                    ->icon('heroicon-m-trash')
                                    ->color('danger')
                                    ->tooltip('Clear all assignees')
                                    ->action(fn ($set) => $set('assignee_ids', []))
                            ),
                    ])
                    ->action(function (Contract $record, array $data) {
                        $assigneeIds = $data['assignee_ids'] ?? [];
                        $record->assignees()->sync($assigneeIds);

                        if (count($assigneeIds) > 0 && in_array($record->status, ['pending', 'proponent-pending'])) {
                            $record->update(['status' => 'in-progress']);
                        } else {
                            foreach ($record->assignees as $assignee) {
                                if ($assignee->EmpEmailAd) {
                                    Notification::route('mail', $assignee->EmpEmailAd)
                                        ->notify(new ContractStatusUpdated($record));
                                }
                            }
                        }

                        $this->dispatch('kanban-board-refresh');
                    }),
                Action::make('markForApproval')
                    ->label('For Approval')
                    ->icon('heroicon-o-document-check')
                    ->color('primary')
                    ->visible(fn (Contract $record): bool => $record->status === 'in-progress')
                    ->form([
                        Textarea::make('remarks')->label('Remarks')->required(),
                    ])
                    ->modalHeading('Move to For Approval')
                    ->modalDescription('Are you sure you want to move this contract to For Approval? Make sure all necessary information is attached.')
                    ->modalSubmitActionLabel('Yes, move it')
                    ->action(function (Contract $record, array $data) {
                        $record->contractRemarks()->create(['remark' => $data['remarks'], 'user_id' => auth()->id()]);
                        $this->forceMoveCard($record->id, 'for-approval');
                        $this->dispatch('kanban-board-refresh');
                    }),
                Action::make('markForExecution')
                    ->label('For Execution')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->visible(fn (Contract $record): bool => $record->status === 'for-approval')
                    ->form([
                        Textarea::make('remarks')->label('Remarks')->required(),
                    ])
                    ->modalHeading('Move to For Execution')
                    ->modalDescription('Are you sure you want to move this contract to For Execution?')
                    ->modalSubmitActionLabel('Yes, move it')
                    ->action(function (Contract $record, array $data) {
                        $record->contractRemarks()->create(['remark' => $data['remarks'], 'user_id' => auth()->id()]);
                        $this->forceMoveCard($record->id, 'for-execution');
                        $this->dispatch('kanban-board-refresh');
                    }),
                Action::make('markExecuted')
                    ->label('Execute')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Contract $record): bool => in_array($record->status, ['for-execution']))
                    ->form([
                        Textarea::make('remarks')->label('Remarks')->required(),
                        FileUpload::make('attachment')
                            ->label('Attachment')
                            ->required()
                            ->directory('contract-attachments'),
                    ])
                    ->modalHeading('Move to Executed')
                    ->modalDescription('Are you sure you want to mark this contract as Executed? This action cannot be undone easily.')
                    ->modalSubmitActionLabel('Yes, execute it')
                    ->action(function (Contract $record, array $data) {
                        $record->contractRemarks()->create(['remark' => $data['remarks'], 'user_id' => auth()->id()]);
                        $record->update(['attachment' => $data['attachment'] ?? null]);
                        $this->forceMoveCard($record->id, 'executed');
                        $this->dispatch('kanban-board-refresh');
                    }),
                Action::make('backToInProgress')
                    ->label('Back to In Progress')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn (Contract $record): bool => in_array($record->status, ['for-approval']))
                    ->form([
                        Textarea::make('remarks')->label('Remarks')->required(),
                    ])
                    ->modalHeading('Move to In Progress')
                    ->modalDescription('Are you sure you want to move this contract to In Progress?')
                    ->modalSubmitActionLabel('Yes, return it')
                    ->action(function (Contract $record, array $data) {
                        $record->contractRemarks()->create(['remark' => $data['remarks'], 'user_id' => auth()->id()]);
                        $this->forceMoveCard($record->id, 'in-progress');
                        $this->dispatch('kanban-board-refresh');
                    }),
                Action::make('viewRemarks')
                    ->label('View Remarks')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('info')
                    ->visible(fn (Contract $record): bool => $record->contractRemarks()->exists())
                    ->modalHeading('Remarks History')
                    ->modalContent(fn (Contract $record) => view('filament.components.remarks-list', [
                        'remarks' => $record->contractRemarks()->latest()->get(),
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),
            ])
            ->cardSchema(fn (Schema $schema): Schema => $schema
                ->components([
                    TextEntry::make('contract_type')
                        ->hiddenLabel()
                        ->badge()
                        ->size('lg')
                        ->color('primary'),
                    TextEntry::make('assignees.EmpLName')
                        ->hiddenLabel()
                        ->icon('heroicon-o-user')
                        ->badge()
                        ->color('gray')
                        ->placeholder('Unassigned')
                        ->visible(fn (Contract $record): bool => $record->status !== 'pending' && $record->status !== 'proponent-pending')
                        ->formatStateUsing(fn (string $state): string => 'Atty. '.$state),
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
                        TextEntry::make('remarks_count')
                            ->hiddenLabel()
                            ->state(fn (Contract $record): int => $record->contractRemarks()->count())
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
            Action::make('switch')
                ->label('Switch to Detailed View')
                ->url(fn (): string => ContractResource::getUrl('list'))
                ->color('gray')
                ->visible(fn () => auth()->user()->can('create-contract')),
            Action::make('create')
                ->label('New Contract')
                ->url(fn (): string => ContractResource::getUrl('create'))
                ->visible(fn () => auth()->user()->can('create-contract')),

            Action::make('confirmMove')
                ->extraAttributes(['style' => 'display: none;'])
                ->form([
                    Textarea::make('remarks')->label('Remarks')->required(),
                ])
                ->modalHeading('Move to For Approval')
                ->modalDescription('Are you sure you want to move this contract to For Approval? Make sure all necessary information is attached.')
                ->modalSubmitActionLabel('Yes, move it')
                ->action(function (array $arguments, array $data) {
                    $contract = Contract::find($arguments['cardId']);
                    $contract?->contractRemarks()->create(['remark' => $data['remarks'], 'user_id' => auth()->id()]);
                    $this->forceMoveCard(
                        $arguments['cardId'],
                        $arguments['targetColumnId'],
                        $arguments['afterCardId'] ?? null,
                        $arguments['beforeCardId'] ?? null
                    );
                }),
            Action::make('confirmMoveToForExecution')
                ->extraAttributes(['style' => 'display: none;'])
                ->form([
                    Textarea::make('remarks')->label('Remarks')->required(),
                ])
                ->modalHeading('Move to For Execution')
                ->modalDescription('Are you sure you want to move this contract to For Execution?')
                ->modalSubmitActionLabel('Yes, move it')
                ->action(function (array $arguments, array $data) {
                    $contract = Contract::find($arguments['cardId']);
                    $contract?->contractRemarks()->create(['remark' => $data['remarks'], 'user_id' => auth()->id()]);
                    $this->forceMoveCard(
                        $arguments['cardId'],
                        $arguments['targetColumnId'],
                        $arguments['afterCardId'] ?? null,
                        $arguments['beforeCardId'] ?? null
                    );
                }),
            Action::make('confirmMoveToExecuted')
                ->extraAttributes(['style' => 'display: none;'])
                ->form([
                    Textarea::make('remarks')->label('Remarks')->required(),
                    FileUpload::make('attachment')
                        ->label('Attachment')
                        ->required()
                        ->directory('contract-attachments'),
                ])
                ->modalHeading('Move to Executed')
                ->modalDescription('Are you sure you want to mark this contract as Executed? This action cannot be undone easily.')
                ->modalSubmitActionLabel('Yes, execute it')
                ->action(function (array $arguments, array $data) {
                    $contract = Contract::find($arguments['cardId']);
                    $contract?->contractRemarks()->create(['remark' => $data['remarks'], 'user_id' => auth()->id()]);
                    $contract?->update(['attachment' => $data['attachment'] ?? null]);
                    $this->forceMoveCard(
                        $arguments['cardId'],
                        $arguments['targetColumnId'],
                        $arguments['afterCardId'] ?? null,
                        $arguments['beforeCardId'] ?? null
                    );
                }),
            Action::make('confirmReturnToInProgress')
                ->extraAttributes(['style' => 'display: none;'])
                ->form([
                    Textarea::make('remarks')->label('Remarks')->required(),
                ])
                ->modalHeading('Return to In Progress')
                ->modalDescription('Are you sure you want to return this contract to In Progress?')
                ->modalSubmitActionLabel('Yes, return it')
                ->action(function (array $arguments, array $data) {
                    $contract = Contract::find($arguments['cardId']);
                    $contract?->contractRemarks()->create(['remark' => $data['remarks'], 'user_id' => auth()->id()]);
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

        if (! in_array($targetColumnId, ['pending', 'proponent-pending']) && $contract?->assignees()->count() === 0) {
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

        if ($contract->status === 'for-approval' && $targetColumnId === 'for-execution') {
            $this->mountAction('confirmMoveToForExecution', [
                'cardId' => $cardId,
                'targetColumnId' => $targetColumnId,
                'afterCardId' => $afterCardId,
                'beforeCardId' => $beforeCardId,
            ]);

            $this->dispatch('kanban-board-refresh');

            return;
        }

        if ($contract->status === 'for-execution' && $targetColumnId === 'executed') {
            $this->mountAction('confirmMoveToExecuted', [
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
