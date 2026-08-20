<?php

namespace App\Filament\Clusters\Setting\Users;

use App\Filament\Clusters\Setting\SettingCluster;
use App\Filament\Clusters\Setting\Users\Pages\CreateUser;
use App\Filament\Clusters\Setting\Users\Pages\EditUser;
use App\Filament\Clusters\Setting\Users\Pages\ListUsers;
use App\Filament\Clusters\Setting\Users\Schemas\UserForm;
use App\Filament\Clusters\Setting\Users\Tables\UsersTable;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = SettingCluster::class;

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
