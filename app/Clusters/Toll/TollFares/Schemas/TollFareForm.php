<?php

namespace App\Filament\Clusters\Toll\TollFares\Schemas;

use App\Models\TollPoint;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TollFareForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Toll Road Details')
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
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
                            ->live()
                            ->afterStateUpdated(fn (callable $set) => $set('entry_point_id', null))
                            ->afterStateUpdated(fn (callable $set) => $set('exit_point_id', null)),
                        Select::make('entry_point_id')
                            ->label('Entry Point')
                            ->options(function (callable $get) {
                                $tollRoadId = $get('toll_road_id');
                                if (! $tollRoadId) {
                                    return [];
                                }

                                return TollPoint::where('toll_road_id', $tollRoadId)
                                    ->whereIn('type', ['entry', 'both'])
                                    ->pluck('name', 'id');
                            })
                            ->required(),
                        Select::make('exit_point_id')
                            ->label('Exit Point')
                            ->options(function (callable $get) {
                                $tollRoadId = $get('toll_road_id');
                                $entryPointId = $get('entry_point_id');
                                if (! $tollRoadId) {
                                    return [];
                                }
                                $query = TollPoint::where('toll_road_id', $tollRoadId)
                                    ->whereIn('type', ['exit', 'both']);
                                // Exclude the entry point if it's selected
                                if ($entryPointId) {
                                    $query->where('id', '!=', $entryPointId);
                                }

                                return $query->pluck('name', 'id');
                            })
                            ->required(),
                    ]),
                Section::make('Fare Matrix')
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->columnSpan([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->description(
                        ''
                    )
                    ->schema([
                        TextInput::make('fare_class_1')
                            ->label('Class 1 Fare (Car, Jeep)')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01),
                        TextInput::make('fare_class_2')
                            ->label('Class 2 Fare (Bus, Truck)')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01),
                        TextInput::make('fare_class_3')
                            ->label('Class 3 Fare (Heavy Vehicle)')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01),
                        TextInput::make('rfid_discount_percent')
                            ->label('RFID Discount (%)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->step(0.01)
                            ->default(0)
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),
            ]);
    }
}
