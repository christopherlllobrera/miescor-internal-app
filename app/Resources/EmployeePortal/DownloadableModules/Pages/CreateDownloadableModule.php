<?php

namespace App\Filament\Resources\EmployeePortal\DownloadableModules\Pages;

use App\Filament\Resources\EmployeePortal\DownloadableModules\DownloadableModuleResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateDownloadableModule extends CreateRecord
{
    protected static string $resource = DownloadableModuleResource::class;

    protected static bool $canCreateAnother = false;

    protected static ?string $title = 'Create Downloadable Form';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Downloadable created')
            ->body('The downloadable has been created successfully.');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! empty($data['form_attachment'])) {
            $file = $data['form_attachment'];

            // Livewire stores temp files at storage/app/livewire-tmp/{filename}
            $path = storage_path('app/livewire-tmp/'.$file);

            if (file_exists($path)) {
                $data['form_attachment'] = file_get_contents($path);
                unlink($path);
            } else {
                // Fallback: try without subdirectory
                $altPath = storage_path('app/'.$file);
                if (file_exists($altPath)) {
                    $data['form_attachment'] = file_get_contents($altPath);
                    unlink($altPath);
                } else {
                    $data['form_attachment'] = null;
                }
            }
        }

        return $data;
    }
}
