<?php

namespace App\Filament\Clusters\NewsPage\Resources\CategoryResource;

use App\Filament\Clusters\NewsPage\NewsPageCluster;
use App\Filament\Clusters\NewsPage\Resources\CategoryResource\Pages\CreateCategory;
use App\Filament\Clusters\NewsPage\Resources\CategoryResource\Pages\EditCategory;
use App\Filament\Clusters\NewsPage\Resources\CategoryResource\Pages\ListCategories;
use App\Filament\Clusters\NewsPage\Resources\CategoryResource\Schemas\CategoryForm;
use App\Filament\Clusters\NewsPage\Resources\CategoryResource\Tables\CategoriesTable;
use App\Models\Category;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static ?int $navigationSort = 2;

    protected static ?string $cluster = NewsPageCluster::class;

    public static function form(Schema $schema): Schema
    {
        return CategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoriesTable::configure($table);
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
            'index' => ListCategories::route('/'),
            // 'create' => CreateCategory::route('/create'),
            // 'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
}
