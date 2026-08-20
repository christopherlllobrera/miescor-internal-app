<?php

namespace App\Filament\Clusters\NewsPage\Resources\PostResource;

use App\Filament\Clusters\NewsPage\NewsPageCluster;
use App\Filament\Clusters\NewsPage\Resources\PostResource\Pages\CreatePost;
use App\Filament\Clusters\NewsPage\Resources\PostResource\Pages\EditPost;
use App\Filament\Clusters\NewsPage\Resources\PostResource\Pages\ListPosts;
use App\Filament\Clusters\NewsPage\Resources\PostResource\Pages\ViewPost;
use App\Filament\Clusters\NewsPage\Resources\PostResource\RelationManagers\CommentsRelationManager;
use App\Filament\Clusters\NewsPage\Resources\PostResource\Schemas\PostForm;
use App\Filament\Clusters\NewsPage\Resources\PostResource\Schemas\PostInfolist;
use App\Filament\Clusters\NewsPage\Resources\PostResource\Tables\PostsTable;
use App\Models\Post;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-newspaper';

    // protected static string | UnitEnum | null $navigationGroup = 'News And Feature';

    protected static ?string $cluster = NewsPageCluster::class;

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return PostForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PostInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PostsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\
            CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'view' => ViewPost::route('/{record}'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
