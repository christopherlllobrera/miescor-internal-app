<?php

namespace App\Filament\Clusters\FAQ\FAQTagModules;

use App\Filament\Clusters\FAQ\FAQCluster;
use App\Filament\Clusters\FAQ\FAQTagModules\Pages\CreateFAQTagModule;
use App\Filament\Clusters\FAQ\FAQTagModules\Pages\EditFAQTagModule;
use App\Filament\Clusters\FAQ\FAQTagModules\Pages\ListFAQTagModules;
use App\Filament\Clusters\FAQ\FAQTagModules\Schemas\FAQTagModuleForm;
use App\Filament\Clusters\FAQ\FAQTagModules\Tables\FAQTagModulesTable;
use App\Models\FAQTagModule;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class FAQTagModuleResource extends Resource
{
    protected static ?string $model = FAQTagModule::class;

    protected static ?string $cluster = FAQCluster::class;

    protected static ?string $navigationLabel = 'Tags';

    protected static ?int $navigationSort = 2;

    protected static ?string $breadcrumb = 'Tags';

    public static function form(Schema $schema): Schema
    {
        return FAQTagModuleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FAQTagModulesTable::configure($table);
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
            'index' => ListFAQTagModules::route('/'),
            // 'create' => CreateFAQTagModule::route('/create'),
            // 'edit' => EditFAQTagModule::route('/{record}/edit'),
        ];
    }
}
