<?php

namespace App\Filament\Resources\Fleet\Dispatchings\Pages;

use App\Filament\Pages\Widgets\DispatchOverview;
use App\Filament\Resources\Fleet\Dispatchings\DispatchingResource;
use App\Filament\Resources\Fleet\Dispatchings\Exports\DispatchingsExporter;
use App\Models\Dispatchings;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListDispatchings extends ListRecords
{
    protected static string $resource = DispatchingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create Dispatching'),
            ExportAction::make()
                ->exporter(DispatchingsExporter::class)
                ->columnMappingColumns(5),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
                ->badge(Dispatchings::count()),
            'Unassigned' => Tab::make()
                ->icon('heroicon-m-question-mark-circle')
                            // ->badge(Dispatchings::where('status', Dispatchings::STATUS_UNASSIGNED)->count())
                            // ->badgeColor('gray')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', Dispatchings::STATUS_UNASSIGNED);
                }),
            'Assigned' => Tab::make()
                ->icon('heroicon-m-clipboard-document-check')
                            // ->badge(Dispatchings::where('status', Dispatchings::STATUS_ASSIGNED)->count())
                            // ->badgeColor('info')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', Dispatchings::STATUS_ASSIGNED);
                }),
            'En Route' => Tab::make()
                ->icon('heroicon-m-arrow-trending-up')
                            // ->badge(Dispatchings::where('status', Dispatchings::STATUS_EN_ROUTE)->count())
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', Dispatchings::STATUS_EN_ROUTE);
                }),
            'Completed' => Tab::make()
                ->icon('heroicon-m-check-circle')
                            // ->badge(Dispatchings::where('status', Dispatchings::STATUS_COMPLETED)->count())
                            // ->badgeColor('success')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', Dispatchings::STATUS_COMPLETED);
                }),
            'Cancelled' => Tab::make()
                ->icon('heroicon-m-x-circle')
                            // ->badge(Dispatchings::where('status', Dispatchings::STATUS_CANCELLED)->count())
                            // ->badgeColor('danger')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', Dispatchings::STATUS_CANCELLED);
                }),
            'Requested' => Tab::make()
                ->icon('heroicon-m-calendar-date-range')
                            // ->badge(Dispatchings::where('status', Dispatchings::STATUS_REQUESTED)->count())
                            // ->badgeColor('danger')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', Dispatchings::STATUS_REQUESTED);
                }),
            'Unserved' => Tab::make()
                ->icon('heroicon-m-face-frown')
                            // ->badge(Dispatchings::where('status', Dispatchings::STATUS_UNSERVED)->count())
                            // ->badgeColor('danger')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', Dispatchings::STATUS_UNSERVED);
                }),
            'Bump-off' => Tab::make()
                ->icon('heroicon-m-hand-raised')
                            // ->badge(Dispatchings::where('status', Dispatchings::STATUS_BUMP_OFF)->count())
                            // ->badgeColor('danger')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', Dispatchings::STATUS_BUMP_OFF);
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            DispatchOverview::class,
        ];
    }
}
