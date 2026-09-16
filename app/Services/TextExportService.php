<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TextExportService
{
    /**
     * Resolve delimiter key or character to actual delimiter character.
     */
    public static function resolveDelimiter(?string $delimiter): string
    {
        return match ($delimiter) {
            'comma', ',' => ',',
            'pipe', '|' => '|',
            default => "\t",
        };
    }

    /**
     * Stream database query to a .txt file.
     *
     * @param  array<string>  $headers
     * @param  \Closure  $rowCallback  fn($record): array
     */
    public static function streamQuery(
        string $filename,
        array $headers,
        Builder $query,
        \Closure $rowCallback,
        string $delimiter = 'tab'
    ): StreamedResponse {
        $actualDelimiter = self::resolveDelimiter($delimiter);

        return response()->streamDownload(function () use ($headers, $query, $rowCallback, $actualDelimiter) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Windows / Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, $headers, $actualDelimiter);

            $query->chunk(200, function ($records) use ($handle, $rowCallback, $actualDelimiter) {
                foreach ($records as $record) {
                    $row = $rowCallback($record);
                    fputcsv($handle, $row, $actualDelimiter);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }

    /**
     * Stream an in-memory collection of records to a .txt file.
     *
     * @param  array<string>  $headers
     * @param  \Closure  $rowCallback  fn($record): array
     */
    public static function streamCollection(
        string $filename,
        array $headers,
        Collection $records,
        \Closure $rowCallback,
        string $delimiter = 'tab'
    ): StreamedResponse {
        $actualDelimiter = self::resolveDelimiter($delimiter);

        return response()->streamDownload(function () use ($headers, $records, $rowCallback, $actualDelimiter) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, $headers, $actualDelimiter);

            foreach ($records as $record) {
                $row = $rowCallback($record);
                fputcsv($handle, $row, $actualDelimiter);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }
}
