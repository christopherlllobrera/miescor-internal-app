<?php

namespace App\Filament\Resources\ContractManagement\Contracts\Pages;

use App\Filament\Resources\ContractManagement\Contracts\ContractResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListContracts extends ListRecords
{
    protected static string $resource = ContractResource::class;

    protected static ?string $title = 'Contract List';

    protected function getHeaderActions(): array
    {
        return [
            ContractResource::backToKanban(),
            CreateAction::make()
                ->label('New Contract'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),
            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending')),
            'proponent-pending' => Tab::make('Pending with Proponent')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'proponent-pending')),
            'in-progress' => Tab::make('In Progress')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'in-progress')),
            'for-approval' => Tab::make('For Approval')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'for-approval')),
            'for-execution' => Tab::make('For Execution')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'for-execution')),
            'executed' => Tab::make('Executed')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'executed')),
            'due' => Tab::make('Due')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'due')),
        ];
    }
}
