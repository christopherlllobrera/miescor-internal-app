<?php

namespace App\Filament\Resources\Fleet\Dispatchings\RelationManagers;

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
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FuelRelationManager extends RelationManager
{
    protected static string $relationship = 'fuel';

    protected static ?string $title = 'Fuel Consumption';

    protected static ?string $recordTitleAttribute = 'AWF';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Fuel Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('AWF')
                            ->label('AWF/SI Number')
                            ->placeholder('Authorization to Withdraw Fuel / Sales Invoice')
                            ->required()
                            ->maxLength(255),
                        Select::make('type')
                            ->label('Fuel Type')
                            ->options([
                                'Diesel' => 'Diesel',
                                'Gasoline' => 'Gasoline',
                                'Electric' => 'Electric',
                            ])
                            ->required(),
                        TextInput::make('liter')
                            ->label('Liters')
                            ->numeric()
                            ->step(0.01)
                            ->suffix('L'),
                        TextInput::make('kilowatt_hour')
                            ->label('Kilowatt Hours')
                            ->numeric()
                            ->step(0.01)
                            ->suffix('kWh')
                            ->visible(fn ($get) => $get('type') === 'Electric'),
                        FileUpload::make('attachment')
                            ->label('Receipt/Attachment')
                            ->disk('public')
                            ->directory('fuel-receipts')
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
                TextColumn::make('AWF')
                    ->label('AWF/SI Number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Fuel Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Diesel' => 'warning',
                        'Gasoline' => 'success',
                        'Electric' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('liter')
                    ->label('Liters')
                    ->numeric(2)
                    ->suffix(' L')
                    ->sortable(),
                TextColumn::make('kilowatt_hour')
                    ->label('kWh')
                    ->numeric(2)
                    ->suffix(' kWh')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                CreateAction::make()
                    ->label('Add Fuel Record'),
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
