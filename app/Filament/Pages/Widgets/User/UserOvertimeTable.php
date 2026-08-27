<?php

namespace App\Filament\Pages\Widgets\User;

use App\Filament\Resources\OvertimeRequests\OvertimeRequestResource;
use App\Models\OvertimeRequestItem;

use Filament\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UserOvertimeTable extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Latest Overtime';

    protected static ?int $sort = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn (): Builder => OvertimeRequestItem::query()
                    ->with('overtimeRequest')
                    ->whereHas('overtimeRequest', function ($query) {
                        $query->where('empNo', Auth::user()?->EmpNo ?? Auth::user()?->empNo);
                    })
                    ->latest('date')
            )
            ->columns([
                TextColumn::make('date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('number_of_hours')
                    ->label('No. of Hours')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('overtimeRequest.status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        default => 'warning',
                    })
                    ->label('Status'),
                TextColumn::make('overtimeRequest.remarks')
                    ->label('Remarks')
                    ->formatStateUsing(fn ($state, OvertimeRequestItem $record) => $record->overtimeRequest?->status === 'Rejected' ? $state : null
                    )
                    ->placeholder('—'),
                TextColumn::make('overtimeRequest.updated_at')
                    ->label('Date Actioned')
                    ->date()
                    ->sortable()
                    ->placeholder('Pending Action') // Shown when not yet approved/rejected
                ,
            ])
            ->deferLoading()
            ->emptyStateHeading('No dispatch yet')
            ->emptyStateDescription('Once you create your first dispatch, it will appear here.')
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('view_all')
                    ->label('View All')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(OvertimeRequestResource::getUrl('index')),
            ])
            ->recordActions([
                Action::make('open')
                    ->label('View')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (OvertimeRequestItem $record): ?string => $record->overtimeRequest
                            ? OvertimeRequestResource::getUrl('edit', ['record' => $record->overtimeRequest])
                            : null
                    )
                    ->visible(fn (OvertimeRequestItem $record): bool => $record->overtimeRequest?->status !== 'Approved'),
            ]);
    }
}
