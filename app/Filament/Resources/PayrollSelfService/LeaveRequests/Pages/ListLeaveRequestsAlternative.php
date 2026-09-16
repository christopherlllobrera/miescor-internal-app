<?php

namespace App\Filament\Resources\PayrollSelfService\LeaveRequests\Pages;

use App\Filament\Resources\PayrollSelfService\LeaveRequests\LeaveRequestResource;
use App\Filament\Resources\PayrollSelfService\LeaveRequests\Tables\LeaveRequestsAlternativeTable;
use App\Models\LeaveRequest;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ListLeaveRequestsAlternative extends ListRecords
{
    protected static string $resource = LeaveRequestResource::class;

    protected static ?string $title = 'Leave Request Review';

    protected static ?string $breadcrumb = 'Review';

    public function table(Table $table): Table
    {
        return LeaveRequestsAlternativeTable::configure($table);
    }

    /**
     * @return array<string | int, Tab>
     */
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->badge(fn (): int => LeaveRequest::query()->count()),
            'pending' => Tab::make('Pending')
                ->badge(fn (): int => LeaveRequest::query()->where('status', 'Pending')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', 'Pending')),
            'approved' => Tab::make('Approved')
                ->badge(fn (): int => LeaveRequest::query()->where('status', 'Approved')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', 'Approved')),
            'rejected' => Tab::make('Rejected')
                ->badge(fn (): int => LeaveRequest::query()->where('status', 'Rejected')->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', 'Rejected')),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('switch_classic_view')
                ->label('My Leaves')
                ->icon('heroicon-o-table-cells')
                ->color('gray')
                ->url(fn (): string => LeaveRequestResource::getUrl('index')),
        ];
    }
}
