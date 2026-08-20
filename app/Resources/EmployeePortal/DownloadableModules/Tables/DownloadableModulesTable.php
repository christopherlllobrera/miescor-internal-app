<?php

namespace App\Filament\Resources\EmployeePortal\DownloadableModules\Tables;

use App\Models\DepartmentModule;
use App\Models\Employee;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DownloadableModulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('form_icon')
                    ->label('Icon')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->imageHeight(50)
                    ->imageWidth(50)
                    ->square(),
                TextColumn::make('department.cms_department_name')
                    ->label('Department')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => DepartmentModule::formatDepartmentName($state)
                    ),
                TextColumn::make('form_title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),
            ])
            ->deferLoading()
            ->emptyStateIcon('heroicon-o-document-plus')
            ->emptyStateHeading('No Downloadable Form yet')
            ->emptyStateDescription('Once you write your first downloadable form, it will appear here.')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Add Downloadable Form')
                    ->url(route('filament.integrated-app.resources.downloadable-forms.create'))
                    ->icon('heroicon-m-plus')
                    ->button(),
            ])
            ->filters([

            ])
            ->recordActions([
                EditAction::make(),
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
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
