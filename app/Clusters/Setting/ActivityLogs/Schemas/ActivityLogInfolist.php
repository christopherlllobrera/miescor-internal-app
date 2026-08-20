<?php

namespace App\Filament\Clusters\Setting\ActivityLogs\Schemas;

use App\Models\ActivityLog;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ActivityLogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                    ])
                    ->columnspanfull(),

                Section::make('Subject Information')
                    ->inlineLabel()
                    ->schema([
                        TextEntry::make('subject_type')
                            ->label('Subject Type')
                            ->formatStateUsing(fn ($state) => $state ? class_basename($state) : 'N/A'),

                        TextEntry::make('subject_id')
                            ->label('Subject ID'),
                    ])
                    ->columnSpan(['lg' => fn (?ActivityLog $record) => $record === null ? 2 : 1])
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
                    ])
                    ->columnSpan(['lg' => 1]),

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
                    ->columnSpan(['lg' => 1])
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
                    ->columnSpan(['lg' => 1])
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
                    ->columnSpan(['lg' => 2])
                    ->visible(function ($record) {
                        $properties = $record->properties ?? [];
                        $otherProps = collect($properties)->except(['old', 'attributes'])->toArray();

                        return ! empty($otherProps);
                    }),
            ])
            ->columns(2);
    }
}
