<?php

namespace App\Filament\Clusters\NewsPage\Resources\PostResource\Tables;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('author.FullName')
                    ->sortable(),
                ImageColumn::make('image')
                    ->imageWidth(128)
                    ->imageHeight(128),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                IconColumn::make('featured')
                    ->boolean(),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                $user = Auth::user();

                // If user has Marketing Team role and is NOT superadmin or TDE Team
                if ($user->hasRole('Marketing Team') &&
                    ! $user->hasAnyRole(['superadmin', 'TDE Team'])) {
                    // Get all user IDs with Marketing Team role
                    $marketingTeamUserIds = User::role('Marketing Team')->pluck('EmpNo');

                    // Only show posts created by Marketing Team members
                    $query->whereIn('user_id', $marketingTeamUserIds);
                }

            })
            ->filters([
                TrashedFilter::make(),
            ])

            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
