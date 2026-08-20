<?php

namespace App\Filament\Clusters\NewsPage\Resources\CommentResource;

use App\Filament\Clusters\NewsPage\NewsPageCluster;
use App\Filament\Clusters\NewsPage\Resources\CommentResource\Pages\CreateComment;
use App\Filament\Clusters\NewsPage\Resources\CommentResource\Pages\EditComment;
use App\Filament\Clusters\NewsPage\Resources\CommentResource\Pages\ListComments;
use App\Filament\Clusters\NewsPage\Resources\CommentResource\Schemas\CommentForm;
use App\Filament\Clusters\NewsPage\Resources\CommentResource\Tables\CommentsTable;
use App\Models\Comment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-bottom-center';

    // protected static string | UnitEnum | null $navigationGroup = 'News And Feature';
    protected static ?string $cluster = NewsPageCluster::class;

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return CommentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CommentsTable::configure($table);
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
            'index' => ListComments::route('/'),
            'create' => CreateComment::route('/create'),
            'edit' => EditComment::route('/{record}/edit'),
        ];
    }
}
