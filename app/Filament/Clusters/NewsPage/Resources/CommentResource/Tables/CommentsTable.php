<?php

namespace App\Filament\Clusters\NewsPage\Resources\CommentResource\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CommentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('post_id')
                    ->label('Post Title')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('comment')
                    ->label('Comment')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
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
