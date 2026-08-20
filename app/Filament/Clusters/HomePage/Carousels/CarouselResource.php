<?php

namespace App\Filament\Clusters\HomePage\Carousels;

use App\Filament\Clusters\HomePage\Carousels\Pages\CreateCarousel;
use App\Filament\Clusters\HomePage\Carousels\Pages\EditCarousel;
use App\Filament\Clusters\HomePage\Carousels\Pages\ListCarousels;
use App\Filament\Clusters\HomePage\Carousels\Schemas\CarouselForm;
use App\Filament\Clusters\HomePage\Carousels\Tables\CarouselsTable;
use App\Filament\Clusters\HomePage\HomePageCluster;
use App\Models\Carousel;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class CarouselResource extends Resource
{
    protected static ?string $model = Carousel::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = HomePageCluster::class;

    public static function form(Schema $schema): Schema
    {
        return CarouselForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CarouselsTable::configure($table);
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
            'index' => ListCarousels::route('/'),
            'create' => CreateCarousel::route('/create'),
            'edit' => EditCarousel::route('/{record}/edit'),
        ];
    }
}
