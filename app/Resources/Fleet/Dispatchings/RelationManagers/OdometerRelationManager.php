<?php

namespace App\Filament\Resources\Fleet\Dispatchings\RelationManagers;

use App\Models\Vehicles;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OdometerRelationManager extends RelationManager
{
    protected static string $relationship = 'odometer';

    protected static ?string $title = 'Odometer Reading';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Odometer Details')
                    ->columns(2)
                    ->schema([
                        Select::make('vehicle_id')
                            ->label('Vehicle')
                            ->options(fn () => Vehicles::orderBy('plate_number')->pluck('plate_number', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('odometer_in')
                            ->label('Odometer In (Start)')
                            ->numeric()
                            ->suffix('km')
                            ->placeholder('Reading at start of trip'),
                        TextInput::make('odometer_out')
                            ->label('Odometer Out (End)')
                            ->numeric()
                            ->suffix('km')
                            ->placeholder('Reading at end of trip'),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vehicle.plate_number')
                    ->label('Vehicle')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('odometer_in')
                    ->label('Odometer In')
                    ->numeric()
                    ->suffix(' km')
                    ->sortable()
                    ->placeholder('Not recorded'),
                TextColumn::make('odometer_out')
                    ->label('Odometer Out')
                    ->numeric()
                    ->suffix(' km')
                    ->sortable()
                    ->placeholder('Not recorded'),
                TextColumn::make('distance')
                    ->label('Distance')
                    ->getStateUsing(function ($record) {
                        if ($record->odometer_in && $record->odometer_out) {
                            return number_format($record->odometer_out - $record->odometer_in, 2).' km';
                        }

                        return null;
                    })
                    ->badge()
                    ->color('success')
                    ->placeholder('N/A'),
                TextColumn::make('created_at')
                    ->label('Recorded')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                CreateAction::make()
                    ->label('Add Reading'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
