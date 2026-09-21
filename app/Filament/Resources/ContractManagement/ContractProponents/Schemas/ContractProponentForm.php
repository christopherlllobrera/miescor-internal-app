<?php

namespace App\Filament\Resources\ContractManagement\ContractProponents\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use App\Models\BusinessUnits;
use App\Models\Department;
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
                        Select::make('business_unit')
                            ->multiple()
                            ->label('Business Units')
                            ->options(BusinessUnits::pluck('BusinessUnitDesc', 'BusinessUnitNo'))
                            ->searchable()
                            ->preload()
                            ->suffixAction(
                                Action::make('clear')
                                    ->icon('heroicon-m-trash')
                                    ->color('danger')
                                    ->tooltip('Clear all business units')
                                    ->action(fn ($set) => $set('business_unit', []))
                            )
                            ->nullable(),
                        Select::make('departments')
                            ->multiple()
                            ->label('Departments')
                            ->options(Department::pluck('DeptDesc', 'DeptNo'))
                            ->searchable()
                            ->preload()
                            ->suffixAction(
                                Action::make('clear')
                                    ->icon('heroicon-m-trash')
                                    ->color('danger')
                                    ->tooltip('Clear all departments')
                                    ->action(fn ($set) => $set('departments', []))
                            )
                            ->nullable(),
                    ]),
            ]);
    }
}
