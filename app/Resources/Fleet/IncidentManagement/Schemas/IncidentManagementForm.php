<?php

namespace App\Filament\Resources\Fleet\IncidentManagement\Schemas;

use App\Models\Vehicles;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class IncidentManagementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Incident Information')
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
                            ])
                            ->columns(2),

                        Section::make('Attachments')
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
                                    ->directory('incident_attachments'),
                            ])->columnSpanFull(),
                    ])->columnSpan(['lg' => 2]),
                Group::make()
                    ->schema([
                        Section::make('Location & Issue')
                            ->description('This section contains information about the location and issue of the incident.')
                            ->icon('heroicon-o-map-pin')
                            ->schema([
                                TextInput::make('location')
                                    ->label('Location')
                                    ->required()
                                    ->columnSpanFull(),
                                Textarea::make('description')
                                    ->label('Description of Incident')
                                    ->required(),
                            ])
                            ->columnSpan(['lg' => 1]),
                    ]),
            ])->columns(3);
    }
}
