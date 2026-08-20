<?php

namespace App\Filament\Resources\DataManagement\Departments\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DepartmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('DeptNo')
                    ->label('Department No')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('DeptDesc')
                    ->label('Department Name')
                    ->sortable()
                    ->searchable(),
            ])
            ->emptyStateIcon('heroicon-o-document-plus')
            ->emptyStateHeading('No Department yet')
            ->emptyStateDescription('Once you write your first department, it will appear here.')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Add Department')
                    ->url(route('filament.integrated-app.resources.department.create'))
                    ->icon('heroicon-m-plus')
                    ->button(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
