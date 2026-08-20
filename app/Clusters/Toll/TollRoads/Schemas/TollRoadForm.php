<?php

namespace App\Filament\Clusters\Toll\TollRoads\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TollRoadForm
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
                    ->description('Use this section to upload a new form and define its display details. Ensure the Title is descriptive. The Icon will help users quickly identify the form type.')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('operator')
                            ->maxLength(255),
                        TextInput::make('region')
                            ->maxLength(255),
                        Textarea::make('description')
                            ->maxLength(1000)
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->default(true),
                    ]),
            ]);
    }
}
