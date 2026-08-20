<?php

namespace App\Filament\Resources\Fleet\Dispatchings\Tables;

use App\Models\Dispatchings;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DispatchingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vea_ticket_number')
                    ->label('VEA Ticket #')
                    ->placeholder('No VEA Ticket No')
                    ->searchable()
                    ->icon(Heroicon::Hashtag)
                    ->iconColor('primary'),

                TextColumn::make('request_item')
                    ->label('RI')
                    ->searchable()
                    ->placeholder('No Controller No')
                    ->iconColor('info'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Unassigned' => 'gray', // working
                        'Assigned' => 'info', // working
                        'En Route' => 'warning', // working
                        'Arrived' => 'success',
                        'Completed' => 'success', // working
                        'Cancelled' => 'danger',
                        'Requested' => 'info',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Unassigned' => 'heroicon-o-question-mark-circle',
                        'Assigned' => 'heroicon-o-clipboard-document-check',
                        'En Route' => 'heroicon-o-arrow-trending-up',
                        'Completed' => 'heroicon-o-check-circle',
                        'Cancelled' => 'heroicon-o-x-circle',
                        'Requested' => 'heroicon-o-calendar-date-range',
                        'Unserved' => 'heroicon-o-face-frown',
                        'Bump-off' => 'heroicon-o-hand-raised',
                    }),

                TextColumn::make('priority_level')
                    ->label('Priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'High' => 'danger',
                        'Medium' => 'warning',
                        'Low' => 'success',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'High' => 'heroicon-o-arrow-trending-up',
                        'Medium' => 'heroicon-o-arrow-right',
                        'Low' => 'heroicon-o-arrow-trending-down',
                    })
                    ->sortable(),

                TextColumn::make('from_location_display')
                    ->label('From')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-m-map-pin')
                    ->limit(40),

                TextColumn::make('to_location_display')
                    ->label('To')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-m-map-pin')
                    ->limit(40),

                TextColumn::make('driver.employee.full_name')
                    ->label('Operator/Driver')
                    ->icon('heroicon-m-user-circle')
                    ->weight(FontWeight::Bold),
                TextColumn::make('vehicle.plate_number')
                    ->icon('heroicon-m-truck')
                    ->label('Plate No.')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->defaultSort('id', 'desc')
            ->deferLoading()
            ->emptyStateHeading('No dispatch yet')
            ->emptyStateDescription('Once you create your first dispatch, it will appear here.')
            ->recordUrl(fn (Dispatchings $record): string => route('filament.integrated-app.resources.Dispatchings.view-route', [
                'record' => $record->id,
            ])
            )
            ->recordActions([
                EditAction::make(),
                ViewAction::make(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('viewRoute')
                    ->label('View Route')
                    ->icon('heroicon-o-map')
                    ->color('success')
                    ->openUrlInNewTab()
                    ->url(fn (Dispatchings $record): string => route('filament.integrated-app.resources.Dispatchings.view-route', ['record' => $record->id])),
                ReplicateAction::make()
                    ->label('Replicate')
                    ->requiresConfirmation(false)
                    ->modalHeading('Replicate Dispatch')
                    ->color('warning')
                    ->modalAlignment(Alignment::Start)
                    ->modalDescription('Are you sure you\'d like to replicate this dispatch?')
                    ->modalSubmitActionLabel('Yes, replicate')
                    ->modalCancelAction(fn (Action $action) => $action->label('Cancel'))
                    // ->modalIcon('heroicon-o-document-duplicate')
                    // ->modalIconColor('info')
                    ->modalWidth(Width::Large)
                    ->excludeAttributes([
                        'vea_ticket_number',
                        'status',
                        'en_route_time',
                        'complete_time',
                        'cancel_time',
                    ])
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Dispatch replicated')
                            ->body('The dispatch has been replicated successfully.'),
                    ),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
