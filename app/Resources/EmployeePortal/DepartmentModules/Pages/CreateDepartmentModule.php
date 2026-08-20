<?php

namespace App\Filament\Resources\EmployeePortal\DepartmentModules\Pages;

use App\Filament\Resources\EmployeePortal\DepartmentModules\DepartmentModuleResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Artisan;

class CreateDepartmentModule extends CreateRecord
{
    protected static string $resource = DepartmentModuleResource::class;

    protected static ?string $title = 'Department Page';

    protected static bool $canCreateAnother = false;

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     Artisan::call('view:clear');

    //     return $data;
    // }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Department Module created')
            ->body('The department module has been created successfully.');
    }

    // protected function getRedirectUrl(): string
    // {
    //     return $this->getResource()::getUrl('index');
    // }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! empty($data['cms_banner'])) {
            $file = $data['cms_banner'];

            // Livewire stores temp files at storage/app/livewire-tmp/{filename}
            $path = storage_path('app/livewire-tmp/'.$file);

            if (file_exists($path)) {
                $data['cms_banner'] = file_get_contents($path);
                unlink($path);
            } else {
                // Fallback: try without subdirectory
                $altPath = storage_path('app/'.$file);
                if (file_exists($altPath)) {
                    $data['cms_banner'] = file_get_contents($altPath);
                    unlink($altPath);
                } else {
                    $data['cms_banner'] = null;
                }
            }
        }

        return $data;
    }
}
