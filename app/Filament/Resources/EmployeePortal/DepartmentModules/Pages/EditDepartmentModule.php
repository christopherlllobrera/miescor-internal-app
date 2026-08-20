<?php

namespace App\Filament\Resources\EmployeePortal\DepartmentModules\Pages;

use App\Filament\Resources\EmployeePortal\DepartmentModules\DepartmentModuleResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditDepartmentModule extends EditRecord
{
    protected static string $resource = DepartmentModuleResource::class;

    protected static ?string $title = 'Edit Department Page';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view')
                ->label('Show')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->url(fn (): string => route('department.show', $this->record->cms_department_slug)),
            DeleteAction::make(),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Department Module updated')
            ->body('The department module has been updated successfully.')
            ->actions([
                Action::make('View', $this->record->cms_department_name)
                    ->button()
                    ->url(
                        route('department.show', $this->record->cms_department_slug),
                        shouldOpenInNewTab: true
                    )
                    ->icon('heroicon-o-eye'),
            ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (empty($data['cms_banner'])) {
            // No new image uploaded — keep the existing binary in DB
            unset($data['cms_banner']);

            return $data;
        }

        $file = $data['cms_banner'];

        // It's a temp filename string (not binary)
        if (is_string($file) && strlen($file) < 255) {
            $path = storage_path('app/livewire-tmp/'.$file);

            if (file_exists($path)) {
                $data['cms_banner'] = file_get_contents($path);
                unlink($path);
            } else {
                $altPath = storage_path('app/'.$file);
                if (file_exists($altPath)) {
                    $data['cms_banner'] = file_get_contents($altPath);
                    unlink($altPath);
                } else {
                    unset($data['cms_banner']);
                }
            }
        }

        return $data;
    }
}
