<?php

namespace App\Filament\Resources\PayrollSelfService\OvertimeRequests\Pages;

use App\Filament\Resources\PayrollSelfService\OvertimeRequests\OvertimeRequestResource;
use App\Filament\Resources\PayrollSelfService\OvertimeRequests\Tables\OvertimeRequestsAlternativeTable;
use App\Models\OvertimeRequest;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ListOvertimeRequestsAlternative extends ListRecords
{
    protected static string $resource = OvertimeRequestResource::class;

    protected static ?string $title = 'Overtime Request Review';

    protected static ?string $breadcrumb = 'Review';

    public function table(Table $table): Table
    {
        return OvertimeRequestsAlternativeTable::configure($table);
    }

    /**
     * @return array<string | int, Tab>
     */
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->badge(fn (): int => OvertimeRequest::query()->count()),
            'pending' => Tab::make('Pending')
                ->badge(fn (): int => OvertimeRequest::query()->where('status', 'Pending')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', 'Pending')),
            'approved' => Tab::make('Approved')
                ->badge(fn (): int => OvertimeRequest::query()->where('status', 'Approved')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', 'Approved')),
            'rejected' => Tab::make('Rejected')
                ->badge(fn (): int => OvertimeRequest::query()->where('status', 'Rejected')->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', 'Rejected')),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('switch_classic_view')
                ->label('My Overtime')
                ->icon('heroicon-o-table-cells')
                ->color('gray')
                ->url(fn (): string => OvertimeRequestResource::getUrl('index')),
        ];
    }
}
