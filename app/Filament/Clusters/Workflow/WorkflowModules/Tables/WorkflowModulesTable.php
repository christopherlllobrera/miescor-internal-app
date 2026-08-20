<?php

namespace App\Filament\Clusters\Workflow\WorkflowModules\Tables;

use App\Models\DepartmentModule;
use App\Models\Employee;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class WorkflowModulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('department.cms_department_name')
                    ->label('Department')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => DepartmentModule::formatDepartmentName($state)
                    ),
                TextColumn::make('workflow_title')
                    ->label('Title')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('tag.workflow_tag_name')
                    ->label('Tag')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('workflow_is_published')
                    ->label('Published')
                    // ->icon(fn (bool $state): string => $state
                    //     ? 'heroicon-o-check-circle'
                    //     : 'heroicon-o-x-circle'
                    // )
                    // ->color(fn (bool $state): string => $state
                    //     ? 'success'
                    //     : 'danger'
                    // )
                    ->boolean()
                    // ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                $user = Auth::user();

                // 1. Check for Superadmin/TDE first to bypass employee validation
                if (! $user) {
                    return $query->whereRaw('1 = 0');
                }

                if ($user->hasRole('superadmin') || $user->hasRole('TDE Team')) {
                    return $query;
                }

                // 2. Employee Check (Only required for non-admins)
                if (! $user->empNo) {
                    return $query->whereRaw('1 = 0');
                }

                $employee = Employee::where('EmpNo', $user->empNo)->first();

                if (! $employee || ! $employee->DeptNo) {
                    return $query->whereRaw('1 = 0');
                }

                // 3. Department PIC Logic
                if ($user->hasRole('Department PIC')) {
                    $deptGroup = substr($employee->DeptNo, 0, 4);

                    return $query->whereHas('department', function (Builder $q) use ($deptGroup) {
                        $q->where('cms_department_name', 'like', $deptGroup.'%');
                    });
                }

                // 4. Fallback: Deny access
                return $query->whereRaw('1 = 0');
            })
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
