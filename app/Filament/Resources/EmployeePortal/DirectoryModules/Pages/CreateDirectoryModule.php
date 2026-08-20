<?php

namespace App\Filament\Resources\EmployeePortal\DirectoryModules\Pages;

use App\Filament\Resources\EmployeePortal\DirectoryModules\DirectoryModuleResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateDirectoryModule extends CreateRecord
{
    protected static string $resource = DirectoryModuleResource::class;

    protected static ?string $title = 'Create Directory Entry';

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Directory Entry Created')
            ->body('The directory entry has been created successfully.');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! empty($data['poc_image'])) {
            $file = $data['poc_image'];

            // Livewire stores temp files at storage/app/livewire-tmp/{filename}
            $path = storage_path('app/livewire-tmp/'.$file);

            if (file_exists($path)) {
                $data['poc_image'] = file_get_contents($path);
                unlink($path);
            } else {
                // Fallback: try without subdirectory
                $altPath = storage_path('app/'.$file);
                if (file_exists($altPath)) {
                    $data['poc_image'] = file_get_contents($altPath);
                    unlink($altPath);
                } else {
                    $data['poc_image'] = null;
                }
            }
        }

        return $data;
    }
}
