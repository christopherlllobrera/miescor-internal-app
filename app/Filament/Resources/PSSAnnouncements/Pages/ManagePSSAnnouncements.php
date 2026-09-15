<?php

namespace App\Filament\Resources\PSSAnnouncements\Pages;

use App\Filament\Resources\PSSAnnouncements\PSSAnnouncementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePSSAnnouncements extends ManageRecords
{
    protected static string $resource = PSSAnnouncementResource::class;

    protected static ?string $title = 'PSS Announcement';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Create')
                ->modalHeading('Create Announcement'),
        ];
    }
}
