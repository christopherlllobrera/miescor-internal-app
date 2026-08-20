<?php

namespace App\Filament\Clusters\HomePage\Events;

use App\Filament\Clusters\HomePage\Events\Pages\CreateEvent;
use App\Filament\Clusters\HomePage\Events\Pages\EditEvent;
use App\Filament\Clusters\HomePage\Events\Pages\ListEvents;
use App\Filament\Clusters\HomePage\Events\Schemas\EventForm;
use App\Filament\Clusters\HomePage\Events\Tables\EventsTable;
use App\Filament\Clusters\HomePage\HomePageCluster;
use App\Models\Event;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?int $navigationSort = 3;

    protected static ?string $cluster = HomePageCluster::class;

    public static function form(Schema $schema): Schema
    {
        return EventForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EventsTable::configure($table);
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
            'index' => ListEvents::route('/'),
            'create' => CreateEvent::route('/create'),
            'edit' => EditEvent::route('/{record}/edit'),
        ];
    }
}
