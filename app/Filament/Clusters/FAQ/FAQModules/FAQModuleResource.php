<?php

namespace App\Filament\Clusters\FAQ\FAQModules;

use App\Filament\Clusters\FAQ\FAQCluster;
use App\Filament\Clusters\FAQ\FAQModules\Pages\CreateFAQModule;
use App\Filament\Clusters\FAQ\FAQModules\Pages\EditFAQModule;
use App\Filament\Clusters\FAQ\FAQModules\Pages\ListFAQModules;
use App\Filament\Clusters\FAQ\FAQModules\Schemas\FAQModuleForm;
use App\Filament\Clusters\FAQ\FAQModules\Tables\FAQModulesTable;
use App\Models\FAQModule;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class FAQModuleResource extends Resource
{
    protected static ?string $model = FAQModule::class;

    protected static ?string $cluster = FAQCluster::class;

    protected static ?string $navigationLabel = 'FAQs';

    protected static ?int $navigationSort = 1;

    protected static ?string $breadcrumb = 'Frequently Asked Questions';

    public static function form(Schema $schema): Schema
    {
        return FAQModuleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FAQModulesTable::configure($table);
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
            'index' => ListFAQModules::route('/'),
            'create' => CreateFAQModule::route('/create'),
            'edit' => EditFAQModule::route('/{record}/edit'),
        ];
    }
}
