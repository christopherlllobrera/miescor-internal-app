<?php

namespace App\Filament\Resources\Fleet\Dispatchings\RelationManagers;

use App\Models\ActivityLog;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    protected static ?string $title = 'Activity History';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('log_name')
                    ->label('Log')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                TextColumn::make('description')
                    ->label('Description')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        return strlen($state) > 50 ? $state : null;
                    }),
                TextColumn::make('event')
                    ->label('Event')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default => 'gray',
                    })
                    ->sortable()
                    ->searchable(),
                TextColumn::make('subject_type')
                    ->label('Subject')
                    ->formatStateUsing(fn ($state) => class_basename($state))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('subject_id')
                    ->label('Subject ID')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('causer.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->default('System'),
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable()
                    ->toggleable()
                    ->since(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('event')
                    ->options([
                        'created' => 'Created',
                        'updated' => 'Updated',
                        'deleted' => 'Deleted',
                    ])
                    ->multiple(),
                SelectFilter::make('log_name')
                    ->options(function () {
                        return ActivityLog::query()
                            ->distinct()
                            ->pluck('log_name', 'log_name')
                            ->toArray();
                    })
                    ->multiple(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->color('info')
                    ->slideOver()
                    ->modalHeading('Activity Logs')
                    ->modalIconColor('info')
                    ->modalDescription('This log provides a detailed audit trail of all user and system activities on this dispatching resource. Use it to track changes, identify the source of an action, and assist with troubleshooting.')
                    ->modalIcon('heroicon-o-document-magnifying-glass')
                    ->schema([
                        Section::make('Activity Details')
                            ->inlineLabel()
                            ->schema([
                                TextEntry::make('log_name')
                                    ->label('Log Name')
                                    ->badge()
                                    ->color('info'),
                                TextEntry::make('event')
                                    ->label('Event')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'created' => 'success',
                                        'updated' => 'warning',
                                        'deleted' => 'danger',
                                        default => 'gray',
                                    }),
                                TextEntry::make('description')
                                    ->label('Description')
                                    ->columnSpanFull(),
                                TextEntry::make('created_at')
                                    ->label('Created At')
                                    ->dateTime(),
                            ]),

                        Section::make('Subject Information')
                            ->inlineLabel()
                            ->schema([
                                TextEntry::make('subject_type')
                                    ->label('Subject Type')
                                    ->formatStateUsing(fn ($state) => $state ? class_basename($state) : 'N/A'),

                                TextEntry::make('subject_id')
                                    ->label('Subject ID'),
                            ])
                            ->visible(fn ($record) => $record->subject_type !== null),
                        Section::make('Causer Information')
                            ->inlineLabel()
                            ->schema([
                                TextEntry::make('causer_type')
                                    ->label('Causer Type')
                                    ->formatStateUsing(fn ($state) => $state ? class_basename($state) : 'System'),

                                TextEntry::make('causer.name')
                                    ->label('Causer Name')
                                    ->default('System'),
                            ]),
                        Section::make('Old Values')
                            ->inlineLabel()
                            ->schema([
                                KeyValueEntry::make('properties.old')
                                    ->keyLabel('Property')
                                    ->valueLabel('Old Value')
                                    ->state(function ($record) {
                                        $old = $record->properties['old'] ?? [];
                                        if (empty($old)) {
                                            return [];
                                        }

                                        // Convert all values to strings
                                        return collect($old)->map(function ($value) {
                                            if (is_array($value)) {
                                                return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                                            }
                                            if (is_bool($value)) {
                                                return $value ? 'true' : 'false';
                                            }
                                            if (is_null($value)) {
                                                return 'null';
                                            }

                                            return (string) $value;
                                        })->toArray();
                                    }),
                            ])
                            ->visible(fn ($record) => ! empty($record->properties['old'] ?? [])),

                        Section::make('New Values')
                            ->inlineLabel()
                            ->schema([
                                KeyValueEntry::make('properties.attributes')
                                    ->keyLabel('Property')
                                    ->valueLabel('New Value')
                                    ->state(function ($record) {
                                        $attributes = $record->properties['attributes'] ?? [];
                                        if (empty($attributes)) {
                                            return [];
                                        }

                                        // Convert all values to strings
                                        return collect($attributes)->map(function ($value) {
                                            if (is_array($value)) {
                                                return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                                            }
                                            if (is_bool($value)) {
                                                return $value ? 'true' : 'false';
                                            }
                                            if (is_null($value)) {
                                                return 'null';
                                            }

                                            return (string) $value;
                                        })->toArray();
                                    }),
                            ])
                            ->visible(fn ($record) => ! empty($record->properties['attributes'] ?? [])),

                        Section::make('Other Properties')
                            ->inlineLabel()
                            ->schema([
                                KeyValueEntry::make('properties')
                                    ->keyLabel('Property')
                                    ->valueLabel('Value')
                                    ->state(function ($record) {
                                        $properties = $record->properties ?? [];
                                        if (empty($properties)) {
                                            return [];
                                        }
                                        // Exclude 'old' and 'attributes' keys, show everything else
                                        $filtered = collect($properties)
                                            ->except(['old', 'attributes'])
                                            ->map(function ($value) {
                                                if (is_array($value)) {
                                                    return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                                                }
                                                if (is_bool($value)) {
                                                    return $value ? 'true' : 'false';
                                                }
                                                if (is_null($value)) {
                                                    return 'null';
                                                }

                                                return (string) $value;
                                            })
                                            ->toArray();

                                        return $filtered;
                                    }),
                            ])
                            ->visible(function ($record) {
                                $properties = $record->properties ?? [];
                                $otherProps = collect($properties)->except(['old', 'attributes'])->toArray();

                                return ! empty($otherProps);
                            }),
                    ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([

                ]),
            ]);
    }
}
