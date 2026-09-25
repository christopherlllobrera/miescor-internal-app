<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class UserAvatarController extends Controller
{
    /**
     * Serve the avatar for the given user.
     */
    public function __invoke(User $user): Response
    {
        // 1. If user has an uploaded custom avatar file on disk
        $avatarPath = $user->getRawOriginal('avatar_url');
        if ($avatarPath && Storage::disk('public')->exists($avatarPath)) {
            $file = Storage::disk('public')->get($avatarPath);
            $mimeType = Storage::disk('public')->mimeType($avatarPath) ?: 'image/jpeg';

            return response($file, 200, [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'public, max-age=86400, must-revalidate',
                'Content-Length' => strlen($file),
            ]);
        }

        // 2. Fall back to linked employee ItemPict binary data
        $itemPict = $user->employee?->ItemPict;
        if (! empty($itemPict)) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($itemPict) ?: 'image/jpeg';

            return response($itemPict, 200, [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'public, max-age=86400, must-revalidate',
                'Content-Length' => strlen($itemPict),
            ]);
        }

        abort(404);
    }
}
