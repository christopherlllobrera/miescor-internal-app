<?php

namespace App\Filament\Resources\EmployeePortal\DownloadableModules\Pages;

use App\Filament\Resources\EmployeePortal\DownloadableModules\DownloadableModuleResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditDownloadableModule extends EditRecord
{
    protected static string $resource = DownloadableModuleResource::class;

    protected static ?string $title = 'Edit Downloadable Form';

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
            ->title('Downloadable updated')
            ->body('The downloadable has been updated successfully.');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (empty($data['form_attachment'])) {
            // No new image uploaded — keep the existing binary in DB
            unset($data['form_attachment']);

            return $data;
        }

        $file = $data['form_attachment'];

        // It's a temp filename string (not binary)
        if (is_string($file) && strlen($file) < 255) {
            $path = storage_path('app/livewire-tmp/'.$file);

            if (file_exists($path)) {
                $data['form_attachment'] = file_get_contents($path);
                unlink($path);
            } else {
                $altPath = storage_path('app/'.$file);
                if (file_exists($altPath)) {
                    $data['form_attachment'] = file_get_contents($altPath);
                    unlink($altPath);
                } else {
                    unset($data['form_attachment']);
                }
            }
        }

        return $data;
    }
}
