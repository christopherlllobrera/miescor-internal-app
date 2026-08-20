<?php

namespace App\Filament\Resources\Fleet\Vehicles\Schemas;

use App\Models\Vehicles;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Vehicle Information')
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 3,
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
                    ->schema([

                        TextInput::make('plate_number')
                            ->label('Plate Number')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('model')
                            ->label('Model')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('year_model')
                            ->label('Year Model')
                            ->numeric()
                            ->required()
                            ->maxLength(255),
                        Select::make('vehicle_type')
                            ->label('Vehicle Type')
                            ->required()
                            ->options([
                                'LIGHT' => 'LIGHT',
                                'MEDIUM' => 'MEDIUM',
                                'HEAVY' => 'HEAVY',
                            ]),
                        Select::make('fuel_type')
                            ->label('Fuel Type')
                            ->options([
                                'GASOLINE' => 'GASOLINE',
                                'DIESEL' => 'DIESEL',
                                'LPG' => 'LPG',
                                'GAS' => 'GAS',
                            ])
                            ->required(),
                        Select::make('vehicle_category')
                            ->label('Vehicle Category')
                            ->options([
                                'PICK UP' => 'PICK UP',
                                'AUV' => 'AUV',
                                'VAN' => 'VAN',
                                'SEDAN' => 'SEDAN',
                                'TRUCK' => 'TRUCK',
                                'MOTORCYCLE' => 'MOTORCYCLE',
                            ])
                            ->required(),
                        TextInput::make('maker')
                            ->required()
                            ->maxLength(255),
                    ]),
                Section::make('Business Information')
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 3,
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
                    ->schema([
                        TextInput::make('company_no')
                            ->label('Company No.')
                            ->maxLength(255),
                        TextInput::make('business_unit')
                            ->label('Business Unit')
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('wbs_2025')
                            ->label('WBS')
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('project_description')
                            ->label('Project Description')
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('type')
                            ->label('Type')
                            ->maxLength(255)
                            ->required(),
                        Select::make('group')
                            ->options([
                                'Internal' => 'Internal',
                                'External' => 'External',
                            ])
                            ->required(),
                        Select::make('status')
                            ->options([
                                Vehicles::STATUS_AVAILABLE => 'Available',
                                Vehicles::STATUS_IN_USE => 'In Use',
                                Vehicles::STATUS_MAINTENANCE => 'Under Maintenance',
                                Vehicles::STATUS_OUT_OF_SERVICE => 'Out of Service',
                            ])
                            ->default(Vehicles::STATUS_AVAILABLE)
                            ->required(),
                    ]),
            ]);
    }
}
