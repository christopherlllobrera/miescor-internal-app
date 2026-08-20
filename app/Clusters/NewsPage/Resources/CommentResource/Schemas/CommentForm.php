<?php

namespace App\Filament\Clusters\NewsPage\Resources\CommentResource\Schemas;

use App\Models\Employee;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;

class CommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Comment Details')
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
                    ->schema([
                        Select::make('user_id')
                            ->label('User')
                            ->options(
                                fn (): Collection => Employee::all()->pluck('full_name', 'EmpNo')
                            )
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('post_id')
                            ->label('Post')
                            ->relationship('post', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('comment')
                            ->label('Comment')
                            ->required()
                            ->minLength(1)
                            ->maxLength(255),
                    ]),

            ]);
    }
}
