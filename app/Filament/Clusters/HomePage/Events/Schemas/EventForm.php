<?php

namespace App\Filament\Clusters\HomePage\Events\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Carousel Details')
                    ->description('Create a category to organize your posts')
                    ->schema([
                        TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->maxLength(150)
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'required' => 'Title is required',
                                'max' => 'Title must be at most 150 characters',
                                'unique' => 'Title already exists',
                            ]),
                        TextInput::make('description')
                            ->label('Description')
                            ->required()
                            ->maxLength(255)
                            ->validationMessages([
                                'required' => 'Description is required',
                                'max' => 'Description must be at most 255 characters',
                            ]),
                        DatePicker::make('date')
                            ->label('Date')
                            ->validationMessages([
                                'required' => 'Date is required',
                            ]),
                        Select::make('color')
                            ->label('Event Color')
                            ->options([
                                'orange' => '🟠 Orange',
                                'violet' => '🟣 Violet',
                                'pink' => '🩷 Pink',
                                'blue' => '🔵 Blue',
                                'green' => '🟢 Green',
                                'red' => '🔴 Red',
                                'black' => '⚫ Black',
                            ])
                            ->validationMessages([
                                'required' => 'Event Color is required',
                            ])
                            ->default('orange')
                            ->required(),
                    ])
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                    ])
                    ->columnSpan([
                        'default' => 1,
                        'sm' => 2,
                    ]),
            ]);
    }
}
