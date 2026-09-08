<?php

namespace App\Filament\Resources\ContractProponents\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContractProponentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Contract Proponent Information')
                    ->columns(2)
                    ->columnSpanFull()
                    ->components([
                        TextInput::make('proponent_name')
                            ->label('Proponent Name')
                            ->required(),
                        TextInput::make('proponent_code')
                            ->label('Proponent Code')
                            ->required(),
                    ]),
            ]);
    }
}
