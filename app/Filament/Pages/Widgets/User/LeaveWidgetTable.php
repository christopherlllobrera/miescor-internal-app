<?php

namespace App\Filament\Pages\Widgets\User;

use App\Filament\Resources\LeaveRequests\LeaveRequestResource;
use App\Models\LeaveRequest;

use Filament\Actions\Action;

use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LeaveWidgetTable extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Latest Leave Requests';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => LeaveRequest::query())
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->deferLoading()
            ->emptyStateHeading('No Leave Requests yet')
            ->emptyStateDescription('Once you create your first leave request, it will appear here.')
            ->headerActions([
                Action::make('view_all')
                    ->label('View All')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(LeaveRequestResource::getUrl('index')),
            ])
            ->recordActions([
                //
            ]);
    }
}
