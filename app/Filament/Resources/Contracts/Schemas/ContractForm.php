<?php

namespace App\Filament\Resources\Contracts\Schemas;

use Carbon\Carbon;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ContractForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Contract Information')
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('reference_no')
                            ->label('Contract Reference No.')
                            ->prefix(fn (Get $get) => 'COCO-'.($get('proponent') ?? '___').'-'.date('y').'-')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Select::make('assigned_to')
                            ->label('Assign To')
                            ->relationship(
                                name: 'assignee',
                                titleAttribute: 'EmpLName',
                                modifyQueryUsing: fn ($query) => $query->whereHas('position', function ($q) {
                                    $q->where('PostDesc', 'Legal Counsel');
                                })
                            )
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name ?? "{$record->EmpLName}, {$record->EmpFName}")
                            ->preload()
                            ->searchable(['EmpLName', 'EmpFName']),
                        Select::make('proponent')
                            ->label('Contract Proponent')
                            ->relationship('contractProponent', 'proponent_name')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->live(),
                        Select::make('contract_type')
                            ->label('Type of Contract')
                            ->options([
                                'Contract' => 'Contract',
                                'Other Documents' => 'Other Documents',
                                'Corporate House Keeping' => 'Corporate House Keeping',
                                'Bid' => 'Bid',
                                'Compliance' => 'Compliance',
                            ])
                            ->preload()
                            ->searchable()
                            ->live()
                            ->required(),
                        FileUpload::make('attachment')
                            ->label('Attachment')
                            ->disk('local')
                            ->directory('contracts')
                            ->columnSpanFull(),
                        Textarea::make('contract_description')
                            ->label('Contract Description')
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('remarks')
                            ->label('Remarks')
                            ->columnSpanFull(),
                        Checkbox::make('has_turnaround_time')
                            ->label('Has turnaround time')
                            ->default(false)
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set, bool $state): void {
                                $set(
                                    'turnaround_date',
                                    $state ? now()->addDays((int) ($get('turnaround_days') ?? 3))->toDateString() : null,
                                );
                            })
                            ->visible(fn (Get $get): bool => ! in_array($get('contract_type'), ['Bid', 'Compliance'])),
                        TextInput::make('turnaround_days')
                            ->label('Turnaround Days')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->visible(fn (Get $get): bool => (bool) $get('has_turnaround_time') && ! in_array($get('contract_type'), ['Bid', 'Compliance'])),
                        DatePicker::make('turnaround_date')
                            ->label('Turnaround Date')
                            ->prefixIcon('heroicon-o-calendar')
                            ->default(fn (): string => now()->addDays(3)->toDateString())
                            ->required(fn (Get $get) => (bool) $get('has_turnaround_time'))
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state): void {
                                if ($state) {
                                    $set('turnaround_days', (int) now()->startOfDay()->diffInDays(Carbon::parse($state)->startOfDay(), false));
                                }
                            })
                            ->visible(fn (Get $get): bool => (bool) $get('has_turnaround_time') && ! in_array($get('contract_type'), ['Bid', 'Compliance'])),
                        DatePicker::make('deadline')
                            ->label('Deadline')
                            ->prefixIcon('heroicon-o-calendar')
                            ->required()
                            ->visible(fn (Get $get): bool => in_array($get('contract_type'), ['Bid', 'Compliance'])),
                    ]),
            ]);
    }
}
