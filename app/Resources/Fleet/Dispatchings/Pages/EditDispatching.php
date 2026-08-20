<?php

namespace App\Filament\Resources\Fleet\Dispatchings\Pages;

use App\Filament\Resources\Fleet\Dispatchings\DispatchingResource;
use App\Models\Odometer;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EditDispatching extends EditRecord
{
    protected static string $resource = DispatchingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load odometer values from related record
        $odometer = Odometer::where('dispatch_id', $this->record->id)->first();
        if ($odometer) {
            $data['odometer_out'] = $odometer->odometer_out;
            $data['odometer_in'] = $odometer->odometer_in;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Handle odometer update/create
        if (! empty($data['odometer_out']) || ! empty($data['odometer_in'])) {
            $odometer = Odometer::where('dispatch_id', $this->record->id)->first();

            if ($odometer) {
                $odometer->update([
                    'vehicle_id' => $data['vehicle_id'] ?? null,
                    'odometer_out' => $data['odometer_out'] ?? null,
                    'odometer_in' => $data['odometer_in'] ?? null,
                ]);
            } else {
                Odometer::create([
                    'dispatch_id' => $this->record->id,
                    'vehicle_id' => $data['vehicle_id'] ?? null,
                    'odometer_out' => $data['odometer_out'] ?? null,
                    'odometer_in' => $data['odometer_in'] ?? null,
                ]);
            }
        }

        // Remove temp fields
        unset($data['odometer_out'], $data['odometer_in']);

        return $data;
    }

    public function form(Schema $form): Schema
    {
        $originalForm = static::getResource()::form($form);
        $components = $originalForm->getComponents()[0]->getChildComponents();
        $sections = [];
        foreach ($components as $component) {

            if (method_exists($component, 'getChildComponents')) {
                $wizardSteps = $component->getChildComponents();
                foreach ($wizardSteps as $step) {
                    $sections[] = Section::make($step->getHeading())
                        ->description($step->getDescription())
                        ->icon($step->getIcon())
                        ->schema($step->getChildComponents())
                        ->columns([
                            'default' => 1,
                            'sm' => 2,
                            'md' => 2,
                            'lg' => 2,
                            'xl' => 2,
                            '2xl' => 2,
                        ])->columnSpan([
                            'default' => 1,
                            'sm' => 2,
                            'md' => 2,
                            'lg' => 2,
                            'xl' => 2,
                            '2xl' => 2,
                        ]);
                }
            }
        }

        return $form->schema($sections);
    }
}
