<?php

namespace App\Filament\Pages\Widgets;

use App\Models\PSSAnnouncement as AnnouncementModel;
use Filament\Widgets\Widget;

class PSSAnnouncement extends Widget
{
    protected string $view = 'filament.pages.widgets.pss-announcement';

    protected int|string|array $columnSpan = 'full';

    public $announcements = [];

    public function mount(): void
    {
        // Get all active announcements within the current period
        $this->announcements = AnnouncementModel::whereDate('date_published', '<=', now())
            ->whereDate('ends_date', '>=', now())
            ->orderByDesc('date_published')
            ->get();
    }
}
