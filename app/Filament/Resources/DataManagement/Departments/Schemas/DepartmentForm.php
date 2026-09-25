<?php

namespace App\Filament\Resources\DataManagement\Departments\Schemas;

use App\Models\Location;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class DepartmentForm
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
                    ->description('Provide the unique department number, name, business unit, cost center, and location used for internal records and reporting.')
                    ->schema([
                        TextInput::make('DeptNo')
                            ->label('Department No.')
                            // ->extraInputAttributes(['onChange' => 'this.value = this.value.toUpperCase()'])
                            ->placeholder('Enter the department number')
                            ->unique(ignoreRecord: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('DeptNo', strtoupper($state)))
                            ->hint(function ($record) {
                                if ($record) {
                                    return null;
                                } else {
                                    return new HtmlString('Use <strong>Uppercase</strong>');
                                }
                            })
                            ->required(),
                        TextInput::make('DeptDesc')
                            ->label('Department Name')
                            ->extraInputAttributes(['onChange' => 'this.value = this.value.toUpperCase()'])
                            ->afterStateUpdated(fn ($state, callable $set) => $set('DeptDesc', strtoupper($state)))->unique(ignoreRecord: true),
                        // ->required()
                        Select::make('BUNo')
                            ->label('Business Unit')
                            ->relationship('businessUnit', 'BusinessUnitDesc')
                            ->searchable()
                            ->preload(),
                        TextInput::make('CostCntrNo')
                            ->label('Cost Center No.')
                            ->placeholder('e.g. MIEA100')
                            ->maxLength(150),
                        Select::make('LocNo')
                            ->label('Location')
                            ->options(function () {
                                return Location::orderBy('LocDesc')
                                    ->get()
                                    ->mapWithKeys(function ($location) {
                                        $id = (int) str_replace('Code', '', $location->LocCode ?? '');

                                        return $id > 0 ? [$id => "{$location->LocDesc} ({$location->LocCode})"] : [];
                                    })
                                    ->filter()
                                    ->toArray();
                            })
                            ->searchable()
                            ->preload(),
                    ]),

            ]);
    }
}
