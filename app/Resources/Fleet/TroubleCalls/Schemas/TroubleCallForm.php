<?php

namespace App\Filament\Resources\Fleet\TroubleCalls\Schemas;

use App\Models\TroubleCall;
use App\Models\Vehicles;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class TroubleCallForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Trouble Call Information')
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 3,
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
                    ->description('This section contains information about the trouble call.')
                    ->icon('heroicon-o-phone')
                    ->schema([
                        Select::make('plate_number')
                            ->label('Vehicle')
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
                            }),
                        TextInput::make('company_no')
                            ->helperText('This will be automatically filled based on the selected vehicle.')
                            ->label('Company No.')
                            ->dehydrated(true)
                            ->readOnly(),
                        TextInput::make('trouble_call_no')
                            ->label('Trouble Call No.')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->readOnly()
                            ->dehydrated(true)
                            ->default(function () {
                                $lastTroubleCallId = TroubleCall::orderBy('id', 'desc')->first()?->ucr_ref_id;
                                if ($lastTroubleCallId) {
                                    // Extract the numeric part (assuming format "UCR-CE-xxxxxx")
                                    $lastNumber = (int) Str::afterLast($lastTroubleCallId, '-');

                                    return 'Trouble-Call-'.str_pad(++$lastNumber, 6, '0', STR_PAD_LEFT);
                                } else {
                                    // Handle the case where no UCR reference IDs exist yet
                                    // (Consider a default starting point or user input)
                                    return 'Trouble-Call-000001'; // Example default (adjust as needed)
                                }
                            }),
                        TextInput::make('reported_by')
                            ->label('Reported By')
                            ->required(),
                        DatePicker::make('reported_date')
                            ->label('Reported Date')
                            ->required()
                            ->default(now()),
                        Select::make('priority')
                            ->label('Priority')
                            ->options([
                                'Low' => 'Low',
                                'Medium' => 'Medium',
                                'High' => 'High',
                            ])
                            ->required()
                            ->default('Medium'),
                    ]),
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
                    ->description('This section contains information about the location and issue of the trouble call.')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        TextInput::make('location')
                            ->label('Location')
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Description of Trouble')
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
                    ->description('This section contains attachments related to the trouble call.')
                    ->icon('heroicon-o-paper-clip')
                    ->schema([
                        FileUpload::make('attachment')
                            ->label('Attach Image')
                            ->disk('public')
                            ->visibility('public')
                            ->multiple()
                            ->preserveFilenames()
                            ->maxParallelUploads(3)
                            ->maxFiles(3)
                            ->image()
                            ->imageEditor()
                            ->acceptedFileTypes(['image/jpeg', 'image/png'])
                            ->directory('trouble_call_attachments')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
