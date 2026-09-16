<?php

namespace App\Filament\Resources\PayrollSelfService\AttendanceAuthorizationForms\Pages;

use App\Filament\Resources\PayrollSelfService\AttendanceAuthorizationForms\AttendanceAuthorizationFormResource;
use App\Filament\Resources\PayrollSelfService\AttendanceAuthorizationForms\Tables\AttendanceAuthorizationFormsAlternativeTable;
use App\Models\AttendanceAuth;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ListAttendanceAuthorizationFormsAlternative extends ListRecords
{
    protected static string $resource = AttendanceAuthorizationFormResource::class;

    protected static ?string $title = 'Attendance Authorization Review';

    protected static ?string $breadcrumb = 'Review';

    public function table(Table $table): Table
    {
        return AttendanceAuthorizationFormsAlternativeTable::configure($table);
    }

    /**
     * @return array<string | int, Tab>
     */
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->badge(fn (): int => AttendanceAuth::query()->count()),
            'pending' => Tab::make('Pending')
                ->badge(fn (): int => AttendanceAuth::query()->where('status', 'Pending')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', 'Pending')),
            'approved' => Tab::make('Approved')
                ->badge(fn (): int => AttendanceAuth::query()->where('status', 'Approved')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', 'Approved')),
            'rejected' => Tab::make('Rejected')
                ->badge(fn (): int => AttendanceAuth::query()->where('status', 'Rejected')->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', 'Rejected')),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('switch_classic_view')
                ->label('Back to List')
                ->icon('heroicon-o-table-cells')
                ->color('gray')
                ->url(fn (): string => AttendanceAuthorizationFormResource::getUrl('index')),
        ];
    }
}
