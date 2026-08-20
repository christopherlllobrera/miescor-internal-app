<?php

namespace App\Filament\Resources\DataManagement\Employees\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('EmpNo')->label('Employee No.')->sortable()->searchable(),
                TextColumn::make('EmpLName')->label('Last Name')->sortable()->searchable(),
                TextColumn::make('EmpFName')->label('First Name')->sortable()->searchable(),
                TextColumn::make('position.PostDesc')->label('Position')->sortable()->searchable(),
                TextColumn::make('department.DeptDesc')->label('Department')->sortable()->searchable(),
                TextColumn::make('DateHired')->label('Date Hired')->date(),
                TextColumn::make('DateCreated')->label('Created')->since(),
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
