<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class YouGileService
{
    private string $apiKey;
    private string $baseUrl = 'https://api.yougile.com/api-v2';

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }
    public function sendReport(array $reportData): array
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->apiKey}"
            ])->post($this->baseUrl . '/tasks', [
                    'title' => $reportData['title'],
                    'columnId' => config('services.yougile.reports_column'),
                    'description' => $reportData['description'],
                    'archived' => false,
                    'completed' => false,
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            Log::error('YouGile API Error', [
                'status' => $response->status(),
                'response' => $response->json()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to send report to YouGile'
            ];

        } catch (\Exception $e) {
            Log::error('YouGile Service Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => 'Service error occurred'
            ];
        }
    }
}
