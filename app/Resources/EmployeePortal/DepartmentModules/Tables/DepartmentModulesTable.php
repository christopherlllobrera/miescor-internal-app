<?php

namespace App\Filament\Resources\EmployeePortal\DepartmentModules\Tables;

use App\Models\DepartmentModule;
use App\Models\Employee;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DepartmentModulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('display_name')
                    ->label('Department Name')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function ($query) use ($search) {
                            // Search by DeptNo
                            $query->where('cms_department_name', 'like', "%{$search}%")
                                // Or search by formatted department description
                                ->orWhereHas('department', function ($q) use ($search) {
                                    $q->where('DeptDesc', 'like', "%{$search}%");
                                });
                        });
                    })
                    ->formatStateUsing(fn (string $state): string => DepartmentModule::formatDepartmentName($state)
                    ),
                TextColumn::make('cms_department_description')
                    ->label('Description')
                    ->searchable()
                    ->limit(50),
            ])
            ->emptyStateIcon('heroicon-o-document-plus')
            ->emptyStateHeading('No Department Page yet')
            ->emptyStateDescription('Once you write your first department page, it will appear here.')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Add Department Page')
                    ->url(route('filament.integrated-app.resources.department-pages.create'))
                    ->icon('heroicon-m-plus')
                    ->button(),
            ])
            ->deferLoading()
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
                Action::make('view')
                    ->label('Show')
                    ->icon('heroicon-o-eye')
                    ->url(fn (DepartmentModule $record): string => route('department.show', $record->cms_department_slug))
                    ->color('info'),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
