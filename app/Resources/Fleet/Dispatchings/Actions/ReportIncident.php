<?php

namespace App\Filament\Resources\Fleet\Dispatchings\Actions;

use App\Filament\Resources\Dispatchings\Pages\ViewDispatchingRoute;
use App\Filament\Resources\Fleet\IncidentManagement\Schemas\IncidentManagementForm;
use App\Models\Dispatchings;
use App\Models\IncidentManagement;
use App\Models\Vehicles;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Size;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Log;

class ReportIncident extends Action
{
    public static function make(?string $name = null): static
    {
        $name ??= 'report_incident';

        return parent::make($name)
            ->label('Report Incident')
            ->icon('heroicon-o-exclamation-triangle')
            ->color('warning')
            ->requiresConfirmation(false)
            ->size(Size::Large)
            ->modalWidth(Width::FourExtraLarge)
            ->modalHeading('Report an Incident')
            ->modalDescription('Please provide details about the incident.')
            ->form(fn () => self::formSchema())
            ->fillForm(function ($livewire) {
                /** @var Dispatchings $dispatch */
                $dispatch = $livewire->record;

                // Prevent duplicate incidents
                if ($dispatch->incident_id) {
                    Notification::make()
                        ->title('Incident already linked to this dispatch.')
                        ->body('You can only report one incident per dispatch.')
                        ->warning()
                        ->send();

                    return [];
                }

                // Prefill fields from dispatch
                return [
                    'plate_number' => $dispatch->plate_number,
                    'company_no' => $dispatch->company_no,
                    'location' => $dispatch->to_location,
                    'reported_by' => auth()->user()->name ?? '',
                    'reported_date' => now(),
                ];
            })
            ->modalSubmitActionLabel('Submit Report')
            ->action(fn (array $data, $livewire) => self::handle($data, $livewire));

    }

    protected static function prefillData(Action $action, $livewire): void
    {
        /** @var Dispatchings $dispatch */
        $dispatch = $livewire->record;

        // Prevent duplicate incidents
        if ($dispatch->incident_id) {
            Notification::make()
                ->title('Incident already linked to this dispatch.')
                ->body('You can only report one incident per dispatch.')
                ->warning()
                ->send();

            // Close modal immediately
            $action->cancel();

            return;
        }

        // Prefill fields from dispatch
        $action->fillForm([
            'plate_number' => $dispatch->plate_number,
            'company_no' => $dispatch->company_no,
            'location' => $dispatch->to_location,
            'reported_by' => auth()->user()->name ?? '',
            'reported_date' => now(),
        ]);
    }

    protected static function formSchema(): array
    {
        // Reuse your IncidentManagementForm schema
        return [
            Section::make('Incident Information')
                ->columns([
                    'default' => 1,
                    'sm' => 2,
                    'md' => 2,
                    'lg' => 2,
                    'xl' => 3,
                    '2xl' => 3,
                ])
                ->columnSpan([
                    'default' => 1,
                    'sm' => 2,
                    'md' => 2,
                    'lg' => 2,
                    'xl' => 2,
                    '2xl' => 2,
                ])
                ->description('This section contains information about the incident.')
                ->icon('heroicon-o-phone')
                ->schema([
                    Select::make('incident_type')
                        ->label('Incident Type')
                        ->required()
                        ->options([
                            'Vehicle Condition' => 'Vehicle Condition',
                            'Vehicle Breakdown' => 'Vehicle Breakdown',
                            'Timely Provision of Service' => 'Timely Provision of Service',
                            'Timely Submission of Service Reports' => 'Timely Submission of Service Reports',
                            'Availability of Resource' => 'Availability of Resource',
                            'Overall Safety Performance' => 'Overall Safety Performance',
                            'Business Ethics' => 'Business Ethics',
                            'Trips Declined' => 'Trips Declined',
                            'Other' => 'Other',
                        ]),
                    Select::make('priority')
                        ->label('Priority')
                        ->options([
                            'Low' => 'Low',
                            'Medium' => 'Medium',
                            'High' => 'High',
                        ])
                        ->required()
                        ->default('Medium'),
                    TextInput::make('reported_by')
                        ->label('Reported By')
                        ->required(),
                    DatePicker::make('reported_date')
                        ->label('Reported Date')
                        ->required()
                        ->default(now()),
                    Select::make('plate_number')
                        ->label('Vehicle Plate Number')
                        ->searchable()
                        ->options(fn () => Vehicles::pluck('plate_number', 'plate_number'))
                        ->live()
                        ->afterStateUpdated(function ($state, Set $set) {
                            if ($state) {
                                $vehicle = Vehicles::where('plate_number', $state)->first();
                                if ($vehicle) {
                                    $set('company_no', $vehicle->company_no ?: '');
                                }
                            }
                        })
                        ->afterStateHydrated(function ($state, Set $set, $record) {
                            if ($state && $record) {
                                $vehicle = Vehicles::where('plate_number', $state)->first();
                                if ($vehicle) {
                                    $set('company_no', $vehicle->company_no ?: '');
                                }
                            }
                        })
                        ->required(),
                    TextInput::make('company_no')
                        ->helperText('This will be automatically filled based on the selected vehicle.')
                        ->label('Company No.')
                        ->dehydrated(true)
                        ->readOnly(),
                ])->columns(2),

            Section::make('Location & Issue')
                ->columns([
                    'default' => 1,
                    'sm' => 2,
                    'md' => 2,
                    'lg' => 2,
                    'xl' => 2,
                    '2xl' => 2,
                ])
                ->columnSpan([
                    'default' => 1,
                    'sm' => 2,
                    'md' => 2,
                    'lg' => 2,
                    'xl' => 2,
                    '2xl' => 2,
                ])
                ->description('This section contains information about the location and issue of the incident.')
                ->icon('heroicon-o-map-pin')
                ->schema([
                    TextInput::make('location')
                        ->label('Location')
                        ->required()
                        ->columnSpanFull(),
                    Textarea::make('description')
                        ->label('Description of Incident')
                        ->required()
                        ->columnSpanFull(),
                ]),

            Section::make('Attachments')
                ->columns([
                    'default' => 1,
                    'sm' => 2,
                    'md' => 2,
                    'lg' => 2,
                    'xl' => 2,
                    '2xl' => 2,
                ])
                ->columnSpan([
                    'default' => 1,
                    'sm' => 2,
                    'md' => 2,
                    'lg' => 2,
                    'xl' => 2,
                    '2xl' => 2,
                ])
                ->description('This section contains attachments related to the incident.')
                ->icon('heroicon-o-paper-clip')
                ->schema([
                    FileUpload::make('attachment')
                        ->label('Attach File')
                        ->disk('public')
                        ->visibility('public')
                        ->multiple()
                        ->preserveFilenames()
                        ->maxParallelUploads(3)
                        ->maxFiles(3)
                        ->image()
                        ->imageEditor()
                        ->acceptedFileTypes([
                            'image/jpeg',
                            'image/png',
                            'application/pdf',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        ])
                        ->directory('incident_attachments')
                        ->columnSpanFull(),
                ]),
        ];
    }

    protected static function handle(array $data, $livewire): void
    {
        /** @var ViewDispatchingRoute $livewire */
        $dispatch = $livewire->record;

        try {
            // Create the incident record
            $incident = IncidentManagement::create([
                'company_no' => $data['company_no'] ?? $dispatch->company_no,
                'incident_type' => $data['incident_type'],
                'reported_by' => $data['reported_by'],
                'reported_date' => $data['reported_date'] ?? now(),
                'location' => $data['location'],
                'plate_number' => $data['plate_number'] ?? $dispatch->plate_number,
                'description' => $data['description'],
                'priority' => $data['priority'],
                'status' => 'Open',
                'attachment' => $data['attachment'] ?? null,
            ]);

            // Link the incident to the current dispatch record
            $dispatch->update([
                'incident_id' => $incident->id,
                'priority_level' => strtolower($data['priority']), // optional sync
            ]);

            Notification::make()
                ->title('Incident reported successfully!')
                ->body('This dispatch has been linked to the new incident record.')
                ->success()
                ->send();

        } catch (\Throwable $e) {
            Log::error('ReportIncident failed: '.$e->getMessage());

            Notification::make()
                ->title('Failed to report incident')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
