<?php

namespace App\Filament\Forms\Components;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class HeroiconOptions
{
    public static function all(): array
    {
        return Cache::remember('heroicon-options-all-formatted', 60 * 60 * 24, function () {
            return collect(self::getIconsFromVendor())
                ->mapWithKeys(fn ($svg, $key) => [
                    $key => '<span class="flex items-center gap-2">'.$svg.' <span>'.self::formatLabel($key).'</span></span>',
                ])
                ->toArray();
        });
    }

    public static function simple(): array
    {
        return collect(self::getIconsFromVendor())
            ->mapWithKeys(fn ($svg, $key) => [$key => self::formatLabel($key)])
            ->toArray();
    }

    public static function solid(): array
    {
        return Cache::remember('heroicon-options-solid-formatted', 60 * 60 * 24, function () {
            return collect(self::getIconsFromVendor('s-')) // Only process solid files
                ->mapWithKeys(fn ($svg, $key) => [
                    $key => '<span class="flex items-center gap-2">'.$svg.' <span>'.self::formatLabel($key).'</span></span>',
                ])
                ->toArray();
        });
    }

    protected static function getIconsFromVendor(?string $filePrefixFilter = null): array
    {
        $cacheKey = 'heroicon-options-raw'.($filePrefixFilter ? '-'.$filePrefixFilter : '');

        return Cache::remember($cacheKey, 60 * 60 * 24, function () use ($filePrefixFilter) {
            $icons = [];
            $svgClass = 'w-5 h-5 inline-block text-gray-500';

            $basePath = base_path('vendor/blade-ui-kit/blade-heroicons/resources/svg');

            if (! File::isDirectory($basePath)) {
                return $icons;
            }

            $prefixMap = [
                'o-' => 'heroicon-o-',
                's-' => 'heroicon-s-',
                'm-' => 'heroicon-m-',
                'c-' => 'heroicon-c-',
            ];

            foreach (File::files($basePath) as $file) {
                if ($file->getExtension() !== 'svg') {
                    continue;
                }

                $filename = $file->getFilenameWithoutExtension();

                // Optimization: Skip files that don't match our filter early
                if ($filePrefixFilter && ! Str::startsWith($filename, $filePrefixFilter)) {
                    continue;
                }

                $key = null;
                foreach ($prefixMap as $filePrefix => $heroiconPrefix) {
                    if (Str::startsWith($filename, $filePrefix)) {
                        $name = Str::after($filename, $filePrefix);
                        $key = $heroiconPrefix.$name;
                        break;
                    }
                }

                if (! $key) {
                    continue;
                }

                $svg = File::get($file->getPathname());

                $svg = preg_replace(
                    '/<svg([^>]*)>/',
                    '<svg$1 class="'.$svgClass.'" width="20" height="20">',
                    $svg,
                    1
                );

                $icons[$key] = $svg;
            }

            ksort($icons);

            return $icons;
        });
    }

    protected static function formatLabel(string $key): string
    {
        $name = preg_replace('/^heroicon-[a-z]-/', '', $key);

        return Str::of($name)
            ->replace('-', ' ')
            ->title()
            ->toString();
    }

    public static function getSvg(string $key): ?string
    {
        return self::getIconsFromVendor()[$key] ?? null;
    }

    public static function clearCache(): void
    {
        Cache::forget('heroicon-options-raw');
        Cache::forget('heroicon-options-raw-s-');
        Cache::forget('heroicon-options-all-formatted');
        Cache::forget('heroicon-options-solid-formatted');
    }
}
