<?php

namespace App\Filament\Clusters\Toll\TollPoints\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\GridDirection;

class TollPointForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Department Details')
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 3,
                        '2xl' => 3,
                    ])
                    ->columnSpan([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->description('Use this section to upload a new form and define its display details. Ensure the Title is descriptive. The Icon will help users quickly identify the form type.')
                    ->schema([
                        Select::make('toll_road_id')
                            ->relationship('tollRoad', 'name')
                            ->required()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('operator')
                                    ->maxLength(255),
                                TextInput::make('region')
                                    ->maxLength(255),
                                Textarea::make('description')
                                    ->maxLength(1000),
                            ]),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('highway_name')
                            ->required()
                            ->maxLength(255),
                        Select::make('type')
                            ->options([
                                'entry' => 'Entry Point',
                                'exit' => 'Exit Point',
                                'both' => 'Entry & Exit',
                            ])
                            ->default('both')
                            ->required(),
                        TextInput::make('latitude')
                            ->required()
                            ->numeric()
                            ->step(0.0000001),

                        TextInput::make('longitude')
                            ->required()
                            ->numeric()
                            ->step(0.0000001),
                        Toggle::make('is_active')
                            ->default(true),
                        CheckboxList::make('payment_methods')
                            ->columns(3)
                            ->columnSpan(2)
                            ->gridDirection(GridDirection::Column)
                            ->options([
                                'cash' => 'Cash',
                                // 'rfid' => 'RFID',
                                // 'epass' => 'EPass',
                                'easytrip' => 'EasyTrip RFID',
                                'autosweep' => 'AutoSweep RFID',
                                // 'easydrive' => 'EasyDrive',
                            ]),
                    ]),
            ]);
    }
}
