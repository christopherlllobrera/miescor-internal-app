<?php

namespace App\Filament\Clusters\Setting\Roles\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\GridDirection;

class RolesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Role Details')
                    ->description('Define a unique name for the role to manage access control effectively.')
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
                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->unique(ignoreRecord: true),
                        CheckboxList::make('Permission')
                            ->columnstart(1)
                            ->searchable()
                            ->noSearchResultsMessage('No permission found.')
                            ->relationship('permissions', 'name')
                            ->selectAllAction(
                                fn (Action $action) => $action->label('Select all Permission'),
                            )
                            ->columns(4)
                            ->columnspanfull()
                            ->gridDirection(GridDirection::Row)
                            ->bulkToggleable(),
                    ]),
            ]);
    }
}
