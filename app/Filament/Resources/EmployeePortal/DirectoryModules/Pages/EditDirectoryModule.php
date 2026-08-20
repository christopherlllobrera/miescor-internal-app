<?php

namespace App\Filament\Resources\EmployeePortal\DirectoryModules\Pages;

use App\Filament\Resources\EmployeePortal\DirectoryModules\DirectoryModuleResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditDirectoryModule extends EditRecord
{
    protected static string $resource = DirectoryModuleResource::class;

    protected static ?string $title = 'Edit Directory Entry';

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Directory Entry Updated')
            ->body('The directory entry has been updated successfully.');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (empty($data['poc_image'])) {
            // No new image uploaded — keep the existing binary in DB
            unset($data['poc_image']);

            return $data;
        }

        $file = $data['poc_image'];

        // It's a temp filename string (not binary)
        if (is_string($file) && strlen($file) < 255) {
            $path = storage_path('app/livewire-tmp/'.$file);

            if (file_exists($path)) {
                $data['poc_image'] = file_get_contents($path);
                unlink($path);
            } else {
                $altPath = storage_path('app/'.$file);
                if (file_exists($altPath)) {
                    $data['poc_image'] = file_get_contents($altPath);
                    unlink($altPath);
                } else {
                    unset($data['poc_image']);
                }
            }
        }

        return $data;
    }
}
