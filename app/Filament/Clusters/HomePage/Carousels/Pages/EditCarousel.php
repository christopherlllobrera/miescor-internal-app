<?php

namespace App\Filament\Clusters\HomePage\Carousels\Pages;

use App\Filament\Clusters\HomePage\Carousels\CarouselResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCarousel extends EditRecord
{
    protected static string $resource = CarouselResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (empty($data['image'])) {
            // No new image uploaded — keep the existing binary in DB
            unset($data['image']);

            return $data;
        }

        $file = $data['image'];

        // It's a temp filename string (not binary)
        if (is_string($file) && strlen($file) < 255) {
            $path = storage_path('app/livewire-tmp/'.$file);

            if (file_exists($path)) {
                $data['image'] = file_get_contents($path);
                unlink($path);
            } else {
                $altPath = storage_path('app/'.$file);
                if (file_exists($altPath)) {
                    $data['image'] = file_get_contents($altPath);
                    unlink($altPath);
                } else {
                    unset($data['image']);
                }
            }
        }

        return $data;
    }
}
