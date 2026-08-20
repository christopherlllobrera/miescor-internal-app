<?php

namespace App\Filament\Resources\Fleet\Drivers\Schemas;

use App\Models\Employee;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;

class DriverForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Driver Details')
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
                    ->description('Use this section to upload a new form and define its display details. Ensure the Title is descriptive. The Icon will help users quickly identify the form type.')
                    ->schema([
                        Select::make('empNo')
                            ->label('Name')
                            ->options(
                                fn (): Collection => Employee::where('CompNo', 1103)
                                    ->whereNotNull('PostNo')
                                    ->get()
                                    ->mapWithKeys(fn ($employee) => [
                                        $employee->EmpNo => $employee->full_name,
                                    ])
                            )
                            ->searchable()
                            ->loadingMessage('Loading employee...')
                            ->live(),
                        TextInput::make('contactno')
                            ->label('Contact No.')
                            ->prefix('+63')
                            ->numeric()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TimePicker::make('shift_in')
                            ->label('Shift In')
                            ->required(),
                        TimePicker::make('shift_out')
                            ->label('Shift Out')
                            ->required(),
                        Select::make('status')
                            ->options([
                                'Active' => 'Active',
                                'Inactive' => 'Inactive',
                            ])
                            ->default('Active')
                            ->required()
                            ->columnstart(2),

                    ]),

            ]);
    }
}
