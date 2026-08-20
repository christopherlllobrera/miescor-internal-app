<?php

namespace App\Filament\Clusters\Setting\ActivityLogs\Actions;

use App\Models\ActivityLog;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Enums\Size;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;

class Cleanlogs extends Action
{
    public static function make(?string $name = null): static
    {
        $name ??= 'clean_logs';

        return parent::make($name)
            ->label('Clean Logs')
            ->icon('heroicon-o-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Clean Activity Logs')
            ->modalDescription('This will permanently delete activity logs based on your criteria. This action cannot be undone.')
            ->modalSubmitActionLabel('Clean Logs')
            ->modalWidth(Width::Large)
            ->size(Size::Large)
            ->form(fn () => self::formSchema())
            ->action(fn (array $data) => self::handle($data));
    }

    protected static function formSchema(): array
    {
        return [
            Section::make('Cleanup Settings')
                ->schema([
                    Radio::make('clean_type')
                        ->label('Cleanup Type')
                        ->options([
                            'days' => 'Clean by age (days)',
                            'config' => 'Use config setting ('.config('activitylog.delete_records_older_than_days', 365).' days)',
                            'all' => 'Clean all logs',
                        ])
                        ->default('days')
                        ->live()
                        ->required(),

                    TextInput::make('days')
                        ->label('Delete logs older than (days)')
                        ->numeric()
                        ->default(90)
                        ->helperText('Logs older than this many days will be deleted.')
                        ->visible(fn (Get $get) => $get('clean_type') === 'days')
                        ->required(fn (Get $get) => $get('clean_type') === 'days'),

                    Select::make('log_name')
                        ->label('Specific Log Category (Optional)')
                        ->options(function () {
                            return ActivityLog::query()
                                ->distinct()
                                ->whereNotNull('log_name')
                                ->pluck('log_name', 'log_name')
                                ->toArray();
                        })
                        ->searchable()
                        ->placeholder('All categories')
                        ->helperText('Leave empty to clean all log categories')
                        ->visible(fn (Get $get) => $get('clean_type') !== 'all'),

                    Toggle::make('optimize_table')
                        ->label('Optimize Database Table After Cleanup')
                        ->helperText('Reclaims disk space (may lock table briefly). Recommended for large deletions.')
                        ->default(false),
                ])
                ->columns(1),

            Placeholder::make('preview')
                ->content(function (Get $get) {
                    $query = ActivityLog::query();

                    if ($get('clean_type') === 'days') {
                        $days = (int) ($get('days') ?? 90);
                        $date = now()->subDays($days);
                        $query->where('created_at', '<', $date);
                    } elseif ($get('clean_type') === 'config') {
                        $days = config('activitylog.delete_records_older_than_days', 365);
                        $date = now()->subDays($days);
                        $query->where('created_at', '<', $date);
                    }

                    if ($logName = $get('log_name')) {
                        $query->where('log_name', $logName);
                    }

                    $count = $query->count();

                    if ($count === 0) {
                        return new HtmlString(
                            '<div class="text-sm text-gray-600 dark:text-gray-400">
                                <strong>No logs</strong> will be deleted with these criteria.
                            </div>'
                        );
                    }

                    $color = $count > 10000 ? 'red' : ($count > 1000 ? 'orange' : 'blue');

                    return new HtmlString(
                        "<div class='text-sm'>
                            <span class='font-semibold text-{$color}-600 dark:text-{$color}-400'>{$count} log(s)</span>
                            <span class='text-gray-600 dark:text-gray-400'> will be permanently deleted.</span>
                        </div>"
                    );
                })
                ->columnSpanFull(),
        ];
    }

    protected static function handle(array $data): void
    {
        $query = ActivityLog::query();

        // Apply filters
        if ($data['clean_type'] === 'days') {
            $days = (int) $data['days'];
            $date = now()->subDays($days);
            $query->where('created_at', '<', $date);
        } elseif ($data['clean_type'] === 'config') {
            $days = config('activitylog.delete_records_older_than_days', 365);
            $date = now()->subDays($days);
            $query->where('created_at', '<', $date);
        }

        if (! empty($data['log_name'])) {
            $query->where('log_name', $data['log_name']);
        }

        // Count before deletion
        $count = $query->count();
        $deleted = $query->delete();

        // Optimize if selected
        $optimized = null;
        if ($data['optimize_table']) {
            try {
                DB::statement('OPTIMIZE TABLE activity_log');
                $optimized = true;
            } catch (Exception $e) {
                $optimized = false;
                Log::error('Failed to optimize activity_log table: '.$e->getMessage());
            }
        }

        // Clear cache
        Cache::flush();

        // Build notification body
        $body = "{$deleted} activity log(s) have been permanently deleted.";
        if ($optimized !== null) {
            $body .= $optimized
                ? ' Database table optimized successfully.'
                : ' (Note: Table optimization failed - check logs)';
        }

        Notification::make()
            ->title('Logs Cleaned Successfully')
            ->body($body)
            ->success()
            ->duration(8000)
            ->send();
    }
}
