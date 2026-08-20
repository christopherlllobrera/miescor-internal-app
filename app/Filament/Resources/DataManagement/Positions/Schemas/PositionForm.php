<?php

namespace App\Filament\Resources\DataManagement\Positions\Schemas;

use App\Models\Department;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;

class PositionForm
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
                    ->description('Provide the unique department number and name used for internal records and reporting.')
                    ->schema([
                        TextInput::make('PostNo')
                            ->label('Department No.')
                            // ->extraInputAttributes(['onChange' => 'this.value = this.value.toUpperCase()'])
                            ->placeholder('Enter the position number')
                            ->unique(ignoreRecord: true)
                            ->integer()
                            ->required(),
                        TextInput::make('PostDesc')
                            ->label('Position Name'),
                        Select::make('DeptNo')
                            ->label('Department Name')
                            ->options(
                                fn (): Collection => Department::query()
                                    ->select('DeptNo', 'DeptDesc')
                                    ->get()
                                    ->pluck('DeptDesc', 'DeptNo')
                            )
                            ->required(),
                    ]),
            ]);
    }
}
