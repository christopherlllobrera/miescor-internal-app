<?php

namespace App\Filament\Clusters\Setting;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class SettingCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    public static function canAccess(): bool
    {
        $user = Auth::user();

        // Allow access if user has the 'superadmin' or 'Dispatcher' role
        return $user && ($user->hasRole('superadmin'));
    }
}
