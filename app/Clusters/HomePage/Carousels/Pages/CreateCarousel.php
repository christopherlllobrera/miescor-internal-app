<?php

namespace App\Filament\Clusters\HomePage\Carousels\Pages;

use App\Filament\Clusters\HomePage\Carousels\CarouselResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCarousel extends CreateRecord
{
    protected static string $resource = CarouselResource::class;

    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! empty($data['image'])) {
            $file = $data['image'];

            // Livewire stores temp files at storage/app/livewire-tmp/{filename}
            $path = storage_path('app/livewire-tmp/'.$file);

            if (file_exists($path)) {
                $data['image'] = file_get_contents($path);
                unlink($path);
            } else {
                // Fallback: try without subdirectory
                $altPath = storage_path('app/'.$file);
                if (file_exists($altPath)) {
                    $data['image'] = file_get_contents($altPath);
                    unlink($altPath);
                } else {
                    $data['image'] = null;
                }
            }
        }

        return $data;
    }
}
