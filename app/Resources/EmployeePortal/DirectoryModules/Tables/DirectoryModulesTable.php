<?php

namespace App\Filament\Resources\EmployeePortal\DirectoryModules\Tables;

use App\Models\DepartmentModule;
use App\Models\Employee;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DirectoryModulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    //     ImageColumn::make('poc_image')
                    //         ->circular()
                    //         ->imageSize(128)
                    //         // ->disk('public')
                    //         // ->directory('directory_profiles')
                    //         // ->visibility('public')
                    //         ->checkFileExistence(false),
                    TextColumn::make('employee.full_name')
                        ->label('Name')
                        ->weight(FontWeight::Bold)
                        ->sortable(query: function ($query, string $direction) {
                            return $query
                                ->join('tblEmployee', 'tblEmployee.EmpNo', '=', 'directory_modules.poc_name_id')
                                ->orderBy('tblEmployee.EmpLName', $direction)
                                ->orderBy('tblEmployee.EmpFName', $direction)
                                ->orderBy('tblEmployee.EmpMName', $direction)
                                ->select('directory_modules.*');
                        })
                        ->searchable(query: function ($query, $search) {
                            $query->whereHas('employee', function ($subQuery) use ($search) {
                                $subQuery->where('EmpLName', 'like', "%{$search}%")
                                    ->orWhere('EmpFName', 'like', "%{$search}%")
                                    ->orWhere('EmpMName', 'like', "%{$search}%");
                            });
                        }),

                    TextColumn::make('department.cms_department_name')
                        ->label('Department')
                        ->color('gray')
                        ->sortable()
                        ->formatStateUsing(fn (string $state): string => DepartmentModule::formatDepartmentName($state)
                        ),
                    TextColumn::make('poc_job_position')
                        ->label('Job Position')
                        ->color('gray')
                        ->sortable(),
                ]),

            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->deferLoading()
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
