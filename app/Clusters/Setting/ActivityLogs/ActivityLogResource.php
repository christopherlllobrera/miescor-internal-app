<?php

namespace App\Filament\Clusters\Setting\ActivityLogs;

use App\Filament\Clusters\Setting\ActivityLogs\Pages\ListActivityLogs;
use App\Filament\Clusters\Setting\ActivityLogs\Pages\ViewActivityLog;
use App\Filament\Clusters\Setting\ActivityLogs\Schemas\ActivityLogForm;
use App\Filament\Clusters\Setting\ActivityLogs\Schemas\ActivityLogInfolist;
use App\Filament\Clusters\Setting\ActivityLogs\Tables\ActivityLogsTable;
use App\Filament\Clusters\Setting\SettingCluster;
use App\Models\ActivityLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    // protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentMagnifyingGlass;

    protected static ?int $navigationSort = 4;

    protected static ?string $breadcrumb = 'Activity Logs';

    protected static ?string $slug = 'Activity Logs';

    protected static ?string $cluster = SettingCluster::class;

    protected static ?string $recordTitleAttribute = 'Activity';

    // public static function form(Schema $schema): Schema
    // {
    //     return ActivityLogForm::configure($schema);
    // }

    public static function infolist(Schema $schema): Schema
    {
        return ActivityLogInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ActivityLogsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivityLogs::route('/'),
            'view' => ViewActivityLog::route('/{record}'),
        ];
    }
}
