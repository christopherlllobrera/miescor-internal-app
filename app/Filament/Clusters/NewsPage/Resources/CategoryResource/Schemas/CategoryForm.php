<?php

namespace App\Filament\Clusters\NewsPage\Resources\CategoryResource\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Category Details')
                    ->description('Create a category to organize your posts')
                    ->schema([
                        TextInput::make('title')
                            ->label('Title')
                            ->live(onBlur: true)
                            ->required()->minLength(1)->maxLength(150)
                            ->afterStateUpdated(function (string $operation, $state, Set $set) {
                                if ($operation === 'edit') {
                                    return;
                                }
                                $set('slug', Str::slug($state));
                            })
                            ->required(),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()->minLength(1)
                            ->unique(ignoreRecord: true)
                            ->maxLength(150)
                            ->disabled()->dehydrated(),
                        TextInput::make('text_color')
                            ->label('Text Color')
                            ->nullable(),
                        TextInput::make('bg_color')
                            ->label('Background Color')
                            ->nullable(),
                    ])
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
                    ]),
            ]);
    }
}
