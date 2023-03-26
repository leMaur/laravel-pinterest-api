<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Services\Concerns;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Lemaur\Pinterest\Exceptions\OAuthException;

trait BuildBaseRequest
{
    /**
     * @throws RequestException
     * @throws OAuthException
     */
    public function buildRequestWithToken(): PendingRequest
    {
        if ($this->oauth->missingAccessToken()) {
            $this->oauth()->requestAccessToken();
        }

        if ($this->oauth->isAccessTokenExpired()) {
            $this->oauth()->refreshAccessToken();
        }

        if ($this->oauth->isRefreshTokenExpired()) {
            throw new OAuthException('Pinterest refresh token expired. You should request a new access code by running: `php artisan pinterest:get-access-code-link`');
        }

        return $this->withBaseUrl()
            ->withToken($this->oauth->access_token);
    }

    public function buildRequestWithBasicBase64(): PendingRequest
    {
        $authHeader = 'Basic '.base64_encode($this->config->client_id.':'.$this->config->client_secret);

        return $this->withBaseUrl()
            ->asForm()
            ->withHeaders(['Authorization' => $authHeader]);
    }

    public function withBaseUrl(): PendingRequest
    {
        return Http::baseUrl($this->config->base_url)
            ->connectTimeout(seconds: $this->config->connect_timeout)
            ->timeout(seconds: $this->config->timeout)
            ->when(
                value: $this->config->retry_enabled,
                callback: fn (PendingRequest $client) => $client->retry(
                    times: $this->config->retry_times,
                    sleepMilliseconds: $this->config->retry_sleep
                )
            );
    }
}
