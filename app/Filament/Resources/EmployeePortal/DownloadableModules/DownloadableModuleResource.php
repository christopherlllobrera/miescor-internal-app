<?php

namespace App\Filament\Resources\EmployeePortal\DownloadableModules;

use App\Filament\Resources\EmployeePortal\DownloadableModules\Pages\CreateDownloadableModule;
use App\Filament\Resources\EmployeePortal\DownloadableModules\Pages\EditDownloadableModule;
use App\Filament\Resources\EmployeePortal\DownloadableModules\Pages\ListDownloadableModules;
use App\Filament\Resources\EmployeePortal\DownloadableModules\Schemas\DownloadableModuleForm;
use App\Filament\Resources\EmployeePortal\DownloadableModules\Tables\DownloadableModulesTable;
use App\Models\DownloadableModule;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class DownloadableModuleResource extends Resource
{
    protected static ?string $model = DownloadableModule::class;

    protected static string|UnitEnum|null $navigationGroup = 'Employee Portal';

    protected static ?string $navigationLabel = 'Downloadable Forms';

    protected static ?string $slug = 'downloadable-forms';

    protected static ?string $breadcrumb = 'Downloadable Forms';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return DownloadableModuleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DownloadableModulesTable::configure($table);
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
            'index' => ListDownloadableModules::route('/'),
            'create' => CreateDownloadableModule::route('/create'),
            'edit' => EditDownloadableModule::route('/{record}/edit'),
        ];
    }
}
