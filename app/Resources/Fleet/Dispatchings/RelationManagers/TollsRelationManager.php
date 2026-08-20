<?php

namespace App\Filament\Resources\Fleet\Dispatchings\RelationManagers;

use App\Models\Dispatchings;
use App\Models\TollPoint;
use App\Models\TollRoad;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TollsRelationManager extends RelationManager
{
    protected static string $relationship = 'tolls';

    protected static ?string $title = 'Toll Entries';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Toll Details')
                    ->columns(2)
                    ->schema([
                        Select::make('toll_road_id')
                            ->label('Expressway')
                            ->options(fn () => TollRoad::where('is_active', true)->orderBy('name')->pluck('name', 'id'))
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set) {
                                $set('entry_point_id', null);
                                $set('exit_point_id', null);
                            }),
                        Select::make('vehicle_class')
                            ->label('Vehicle Class')
                            ->options([
                                1 => 'Class 1 (Cars, SUVs)',
                                2 => 'Class 2 (Trucks)',
                                3 => 'Class 3 (Large Trucks)',
                            ])
                            ->default(1)
                            ->required(),
                        Select::make('entry_point_id')
                            ->label('Entry Point')
                            ->options(function (callable $get) {
                                $tollRoadId = $get('toll_road_id');
                                if (! $tollRoadId) {
                                    return [];
                                }

                                return TollPoint::where('toll_road_id', $tollRoadId)
                                    ->where('is_active', true)
                                    ->whereIn('type', ['entry', 'both'])
                                    ->orderBy('name')
                                    ->pluck('name', 'id');
                            })
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('exit_point_id', null)),
                        Select::make('exit_point_id')
                            ->label('Exit Point')
                            ->options(function (callable $get) {
                                $tollRoadId = $get('toll_road_id');
                                $entryPointId = $get('entry_point_id');
                                if (! $tollRoadId || ! $entryPointId) {
                                    return [];
                                }

                                return TollPoint::where('toll_road_id', $tollRoadId)
                                    ->where('is_active', true)
                                    ->whereIn('type', ['exit', 'both'])
                                    ->where('id', '!=', $entryPointId)
                                    ->orderBy('name')
                                    ->pluck('name', 'id');
                            })
                            ->required(),
                        Select::make('payment_method')
                            ->label('Payment Method')
                            ->options(Dispatchings::paymentMethodOptions())
                            ->default(Dispatchings::PAYMENT_METHOD_CASH)
                            ->required(),
                        TextInput::make('toll_fare')
                            ->label('Toll Fare')
                            ->numeric()
                            ->step(0.01)
                            ->prefix('₱')
                            ->required(),
                        FileUpload::make('toll_attachments')
                            ->label('Receipt/Attachment')
                            ->disk('public')
                            ->directory('toll-receipts')
                            ->visibility('public')
                            ->multiple()
                            ->maxFiles(3)
                            ->acceptedFileTypes(['image/*', 'application/pdf'])
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tollRoad.name')
                    ->label('Expressway')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('entryPoint.name')
                    ->label('Entry Point')
                    ->searchable(),
                TextColumn::make('exitPoint.name')
                    ->label('Exit Point')
                    ->searchable(),
                TextColumn::make('vehicle_class')
                    ->label('Class')
                    ->formatStateUsing(fn ($state) => "Class {$state}")
                    ->badge()
                    ->color('gray'),
                TextColumn::make('payment_method')
                    ->label('Payment')
                    ->formatStateUsing(fn ($state) => Dispatchings::paymentMethodOptions()[$state] ?? $state)
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'cash' => 'warning',
                        'autosweep' => 'success',
                        'easytrip' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('toll_fare')
                    ->label('Fare')
                    ->money('PHP')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                CreateAction::make()
                    ->label('Add Toll Entry'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
