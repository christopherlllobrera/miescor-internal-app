<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SharePointService
{
    protected string $tenantId;
    protected string $clientId;
    protected string $clientSecret;
    protected string $graphUrl = 'https://graph.microsoft.com/v1.0';

    public function __construct()
    {
        $this->tenantId = config('services.msgraph.tenant_id');
        $this->clientId = config('services.msgraph.client_id');
        $this->clientSecret = config('services.msgraph.client_secret');
    }

    /**
     * Get an access token using Client Credentials Flow.
     */
    public function getAccessToken(): ?string
    {
        $url = "https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/token";

        $response = Http::asForm()->post($url, [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'scope' => 'https://graph.microsoft.com/.default',
            'grant_type' => 'client_credentials',
        ]);

        if ($response->successful()) {
            return $response->json('access_token');
        }

        Log::error('Microsoft Graph Token Error: ' . $response->body());
        return null;
    }

    /**
     * Lock the file (Checkout).
     */
    public function checkoutFile(string $driveId, string $itemId): bool
    {
        $token = $this->getAccessToken();
        if (!$token) return false;

        $response = Http::withToken($token)
            ->post("{$this->graphUrl}/drives/{$driveId}/items/{$itemId}/checkout");

        if ($response->failed()) {
            Log::error('SharePoint Checkout Error: ' . $response->body());
            return false;
        }

        return true;
    }

    /**
     * Unlock the file (Checkin).
     */
    public function checkinFile(string $driveId, string $itemId, string $comment = 'System update'): bool
    {
        $token = $this->getAccessToken();
        if (!$token) return false;

        $response = Http::withToken($token)
            ->post("{$this->graphUrl}/drives/{$driveId}/items/{$itemId}/checkin", [
                'comment' => $comment,
                'checkInAs' => 'published'
            ]);

        if ($response->failed()) {
            Log::error('SharePoint Checkin Error: ' . $response->body());
            return false;
        }

        return true;
    }
}
