<?php

namespace App\Filament\Resources\Fleet\Drivers\Tables;

use App\Models\Employee;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DriversTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.full_name')
                    ->label('Name')
                    ->sortable(query: function ($query, $direction) {
                        $query->orderBy(
                            Employee::select('EmpLName')
                                ->whereColumn('tblEmployee.EmpNo', 'drivers.empNo'),
                            $direction
                        );
                    })
                    ->searchable(query: function ($query, $search) {
                        $query->whereHas('employee', function ($subQuery) use ($search) {
                            $subQuery->where('EmpLName', 'like', "%{$search}%")
                                ->orWhere('EmpFName', 'like', "%{$search}%")
                                ->orWhere('EmpMName', 'like', "%{$search}%");
                        });
                    }),

                TextColumn::make('contactno')
                    ->label('Contact No')
                    ->searchable()
                    ->sortable()
                    ->prefix('+63')
                    ->copyable()
                    ->copyMessage('Copied!')
                    ->copyMessageDuration(1500)
                    ->icon(Heroicon::DevicePhoneMobile)
                    ->iconPosition(IconPosition::Before),
                TextColumn::make('status')
                    ->label('Status')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Active' => 'success',
                        'Inactive' => 'danger',
                    }),
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
