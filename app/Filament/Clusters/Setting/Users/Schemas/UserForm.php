<?php

namespace App\Filament\Clusters\Setting\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User Profile')
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                    ])
                    ->columnSpan('full')
                    ->schema([
                        TextInput::make('empNo')
                            ->label('Employee No.')
                            ->maxLength(255),
                        TextInput::make('username')
                            ->label('Username')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('comp_email')
                            ->label('Email')
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->maxLength(255)
                            ->dehydrated(fn ($state) => ! is_null($state) && $state !== ''),
                        DateTimePicker::make('password_expires_at')
                            ->label('Password Expires At'),
                        Select::make('access_level')
                            ->label('Roles')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->searchable()
                            ->preload(),
                    ]),
            ]);
    }
}
