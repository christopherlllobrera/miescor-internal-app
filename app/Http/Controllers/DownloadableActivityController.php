<?php

namespace App\Http\Controllers;

use App\Http\Requests\DownloadActivityRequest;
use App\Models\DownloadableModule;
use Illuminate\Http\JsonResponse;

class DownloadableActivityController extends Controller
{
    /**
     * Log a downloadable file click/download.
     */
    public function log(DownloadActivityRequest $request): JsonResponse
    {
        $data = $request->validated();

        $downloadable = DownloadableModule::find($data['downloadable_id']);

        if (! $downloadable) {
            return response()->json(['message' => 'Not found'], 404);
        }

        // Use Spatie activity() helper to create an activity log
        activity()
            ->causedBy($request->user())
            ->performedOn($downloadable)
            ->withProperties([
                'downloadable_id' => $downloadable->id,
                'title' => $downloadable->form_title,
                'attachment' => $downloadable->form_attachment,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->event('downloaded')
            ->log('Downloaded');

        return response()->json(['message' => 'Logged'], 200);
    }
}
