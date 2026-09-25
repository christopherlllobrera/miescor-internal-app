<?php

namespace App\Filament\Clusters\FAQ\FAQModules\Tables;

use App\Models\Department;
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

class FAQModulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('faq_title')
                    ->label('Title')
                    ->searchable()
                    ->limit(50),
                IconColumn::make('faq_is_published')
                    ->label('Published')
                    ->boolean(),
                TextColumn::make('department.cms_department_name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Department')
                    ->formatStateUsing(fn (string $state): string => DepartmentModule::formatDepartmentName($state)
                    ),
                TextColumn::make('tag.faq_tag_name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Tag'),

            ])
            ->filters([
                //
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

                $dept = Department::where('DeptNo', $employee->DeptNo)
                    ->orWhere('CostCntrNo', $employee->DeptNo)
                    ->first();

                $costCenter = $dept?->CostCntrNo ?? $employee->DeptNo;

                // 3. Department PIC Logic
                if ($user->hasRole('Department PIC')) {
                    if (! $costCenter) {
                        return $query->whereRaw('1 = 0');
                    }

                    $deptGroup = substr($costCenter, 0, 4);

                    return $query->whereHas('department', function (Builder $q) use ($deptGroup) {
                        $q->where(function (Builder $sub) use ($deptGroup) {
                            $sub->where('cms_department_cost_center', 'like', $deptGroup.'%')
                                ->orWhere('cms_department_name', 'like', $deptGroup.'%');
                        });
                    });
                }

                // 4. Fallback: Deny access
                return $query->whereRaw('1 = 0');
            })
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
