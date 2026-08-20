<?php

namespace App\Filament\Resources\Fleet\Dispatchings\Pages;

use App\Filament\Resources\Fleet\Dispatchings\DispatchingResource;
use App\Models\Odometer;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;

class ViewDispatching extends ViewRecord implements HasForms
{
    protected static string $resource = DispatchingResource::class;

    public function getTitle(): string|Htmlable
    {
        $record = $this->getRecord();

        return "Dispatching Details - {$record->vehicle?->plate_number}";
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

    protected function getActions(): array
    {
        return [
            Action::make('viewRoute')
                ->label('View Route')
                ->icon('heroicon-o-map')
                ->color('success')
                ->url(fn () => static::getResource()::getUrl('view-route', ['record' => $this->getRecord()])),
            EditAction::make(),
        ];
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
