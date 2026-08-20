<?php

namespace App\Filament\Resources\Fleet\Dispatchings\Actions;

use App\Filament\Resources\Dispatchings\Pages\ViewDispatchingRoute;
use App\Models\Dispatchings;
use App\Models\Fuel;
use App\Models\Odometer;
use App\Models\Toll;
use App\Models\TollPoint;
use App\Models\TollRoad;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Enums\Size;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Log;

class CompleteTrip extends Action
{
    public static function make(?string $name = null): static
    {
        $name ??= 'complete_trip';

        return parent::make($name)
            ->label('Complete Trip')
            ->icon('heroicon-o-check-circle')
            ->color('success')
            ->requiresConfirmation()
            ->size(Size::Large)
            ->modalWidth(Width::FiveExtraLarge)
            ->modalHeading('Complete Trip')
            ->modalDescription('Ensure all trip details are finalized before completion.')
            ->form(self::formSchema())
            ->modalSubmitActionLabel('Yes, complete trip')
            ->action(fn (array $data, $livewire) => self::handle($data, $livewire));
    }

    protected static function formSchema(): array
    {
        return [
            Section::make()
                ->columns([
                    'default' => 1,
                    'sm' => 2,
                    'md' => 2,
                    'lg' => 2,
                    'xl' => 2,
                    '2xl' => 2,
                ])
                ->schema([
                    TextInput::make('odometer_in')
                        ->label('Odometer In')
                        ->placeholder('Enter the current odometer reading')
                        ->numeric()
                        ->required(),
                ]),
            Section::make('Toll Points')
                ->columnspanFull()
                ->description('Add each expressway and toll points manually')
                ->schema([
                    Repeater::make('toll_entries')
                        ->columnspanFull()
                        ->columns([
                            'default' => 1,
                            'sm' => 2,
                            'md' => 2,
                            'lg' => 2,
                            'xl' => 2,
                            '2xl' => 2,
                        ])
                        ->schema([
                            Select::make('toll_road_id')
                                ->label('Expressway')
                                ->options(fn () => TollRoad::where('is_active', true)->orderBy('name')->pluck('name', 'id'))
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn (Set $set) => $set('entry_point_id', null))
                                ->afterStateUpdated(fn (Set $set) => $set('exit_point_id', null)),
                            Select::make('vehicle_class')
                                ->label('Vehicle Class')
                                ->options([1 => 'Class 1 (Cars, SUVs)'])
                                ->default(1)
                                ->required(),
                            Select::make('entry_point_id')
                                ->label('Entry Point')
                                ->options(function (callable $get) {
                                    $tollRoadId = $get('toll_road_id');
                                    if (! $tollRoadId) {
                                        return [];
                                    }

                                    return TollPoint::where('toll_road_id', $tollRoadId)
                                        ->where('is_active', true)
                                        ->whereIn('type', ['entry', 'both'])
                                        ->orderBy('name')
                                        ->pluck('name', 'id');
                                })
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn (Set $set) => $set('exit_point_id', null)),

                            Select::make('exit_point_id')
                                ->label('Exit Point')
                                ->options(function (callable $get) {
                                    $tollRoadId = $get('toll_road_id');
                                    $entryPointId = $get('entry_point_id');
                                    if (! $tollRoadId || ! $entryPointId) {
                                        return [];
                                    }

                                    return TollPoint::where('toll_road_id', $tollRoadId)
                                        ->where('is_active', true)
                                        ->whereIn('type', ['exit', 'both'])
                                        ->where('id', '!=', $entryPointId)
                                        ->orderBy('name')
                                        ->pluck('name', 'id');
                                })
                                ->required()
                                ->live(),
                            Select::make('payment_method')
                                ->label('Payment Method')
                                ->options(Dispatchings::paymentMethodOptions())
                                ->default(Dispatchings::PAYMENT_METHOD_CASH)
                                ->required(),
                            TextInput::make('toll_fare')
                                ->label('Toll Fare')
                                ->numeric()
                                ->prefix('₱'),
                            FileUpload::make('attachment')
                                ->label('Attach File')
                                ->disk('public')
                                ->visibility('public')
                                ->multiple()
                                ->maxFiles(3)
                                ->preserveFilenames()
                                ->directory('incident_attachments')
                                ->acceptedFileTypes([
                                    'image/jpeg', 'image/png', 'application/pdf',
                                    'application/vnd.ms-excel',
                                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                    'application/msword',
                                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                ])
                                ->imageEditor()
                                ->columnSpanFull(),
                        ])
                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string => $state['toll_road_id']
                                ? TollRoad::find($state['toll_road_id'])?->name.' - ₱'.($state['toll_fare'] ?? '0.00')
                                : null
                        )
                        ->addActionLabel('Add Expressway'),
                ]),
            Section::make('Fuel Consumption')
                ->columns([
                    'default' => 1,
                    'sm' => 2,
                    'md' => 2,
                    'lg' => 2,
                    'xl' => 2,
                    '2xl' => 2,
                ])
                ->schema([
                    TextInput::make('AWF')
                        ->label('AWF/SI')
                        ->placeholder('Authorization to Withdraw Fuel/Sales Invoice')
                        ->required(),
                    // TextInput::make('fuel_price')
                    //     ->label('Fuel Price (PHP/liter)')
                    //     ->numeric()
                    //     ->step(0.01)
                    //     ->required()
                    //     ->columnstart(1),
                    Select::make('gas_refill_type')
                        ->label('Fuel Type')
                        ->options([
                            'Diesel' => 'Diesel',
                            'Gasoline' => 'Gasoline',
                            'Electric' => 'Electric',
                        ])
                        ->required()
                        ->live(),
                    TextInput::make('gas_consumption')
                        ->label('Gas Refilled (liters)')
                        ->numeric()
                        ->minValue(0)
                        ->required(),

                    FileUpload::make('gas_receipt')
                        ->label('Upload Gas Receipt')
                        ->preserveFilenames()
                        ->maxSize(2048)
                        ->columnspanFull()
                        ->visible(fn (Get $get) => in_array($get('gas_refill_type'), [
                            Dispatchings::GAS_REFILL_REFILLED,
                            Dispatchings::GAS_REFILL_TOP_UP,
                        ])
                        ),
                ]),
        ];
    }

    protected static function handle(array $data, $livewire): void
    {
        /** @var ViewDispatchingRoute $livewire */
        $record = $livewire->record;

        try {
            // Update odometer record with odometer_in
            $odometer = Odometer::where('dispatch_id', $record->id)->first();
            if ($odometer) {
                $odometer->update([
                    'odometer_in' => $data['odometer_in'] ?? null,
                ]);
            } else {
                Odometer::create([
                    'dispatch_id' => $record->id,
                    'vehicle_id' => $record->vehicle_id,
                    'odometer_in' => $data['odometer_in'] ?? null,
                ]);
            }

            // Create or update fuel record
            $fuel = Fuel::updateOrCreate(
                ['dispatch_id' => $record->id],
                [
                    'AWF' => $data['AWF'] ?? null,
                    'liter' => $data['gas_consumption'] ?? null,
                    'type' => $data['gas_refill_type'] ?? null,
                    'attachment' => $data['gas_receipt'] ?? null,
                ]
            );

            if (! empty($data['toll_entries'])) {
                foreach ($data['toll_entries'] as $entry) {
                    Toll::create([
                        'dispatch_id' => $record->id,
                        'toll_road_id' => $entry['toll_road_id'] ?? null,
                        'vehicle_class' => $entry['vehicle_class'] ?? 1,
                        'entry_point_id' => $entry['entry_point_id'] ?? null,
                        'exit_point_id' => $entry['exit_point_id'] ?? null,
                        'payment_method' => $entry['payment_method'] ?? null,
                        'toll_fare' => $entry['toll_fare'] ?? 0,
                        'toll_attachments' => $entry['attachment'] ?? null,
                    ]);
                }
            }

            // Update dispatch record - only update complete_time since fuel/toll data is in separate tables
            $record->update([
                'complete_time' => now(),
            ]);

            $record->completeTrip($data);

            Notification::make()
                ->title('Trip completed successfully!')
                ->success()
                ->send();

            $livewire->redirectToRoute();

        } catch (\Throwable $e) {
            Log::error('CompleteTrip failed: '.$e->getMessage());

            Notification::make()
                ->title('Something went wrong')
                ->danger()
                ->body($e->getMessage())
                ->send();
        }
    }
}
