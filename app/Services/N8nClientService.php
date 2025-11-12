<?php

declare(strict_types=1);

namespace N8nAutomation\Services;

use Illuminate\Support\Facades\Http;

class N8nClientService
{
    public function post(string $endpoint, array $payload = []): array
    {
        $retryAfter = (int)config('services.n8n_ad_script.retry_after');
        $retries = (int)config('services.n8n_ad_script.retries');
        $userName = config('services.n8n_ad_script.webhook_username');
        $password = config('services.n8n_ad_script.webhook_password');

        $response = Http::withBasicAuth($userName, $password)
                        ->baseUrl(config('services.n8n_ad_script.base_url'))
                        ->retry($retries, $retryAfter)
                        ->asJson()
                        ->post($endpoint, $payload)
                        ->throw();

        return $response->json();
    }
}
