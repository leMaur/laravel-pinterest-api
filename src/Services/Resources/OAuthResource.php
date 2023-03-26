<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Services\Resources;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Lemaur\Pinterest\Data\ConfigData;
use Lemaur\Pinterest\Data\OAuthData;
use Lemaur\Pinterest\Events\CredentialsRetrieved;
use Lemaur\Pinterest\Services\Pinterest;
use Lemaur\Pinterest\Services\Resources\Contracts\OAuthResourceContract;
use OAuthException;

/**
 * Pinterest's authentication flow
 *
 * @see https://developers.pinterest.com/docs/getting-started/authentication/
 */
class OAuthResource implements OAuthResourceContract
{
    public const ENDPOINT = '/oauth/token';

    /**
     * @throws OAuthException
     */
    public function __construct(
        private readonly Pinterest $service,
        private readonly ConfigData $config,
        private readonly OAuthData $oauth,
    ) {
        if (! $this->oauth->access_code || ! $this->oauth->refresh_token) {
            throw new OAuthException("Unable to find Pinterest credentials. Please, check App\Providers\PinterestServiceProvider configuration.");
        }
    }

    public function credentials(): OAuthData
    {
        return $this->oauth;
    }

    /**
     * Request user access and receive the access code with your redirect URI
     *
     * @see https://developers.pinterest.com/docs/getting-started/authentication/#Generating%20an%20access%20token
     */
    public function getAccessCodeLink(): string
    {
        $state = Str::random(64);

        Cache::put('pinterest_api::oauth_state', $state, Date::now()->addMinutes(5));

        $query = [
            'client_id' => $this->config->client_id,
            'redirect_uri' => $this->config->oauth_redirect_uri,
            'response_type' => 'code',
            'scope' => implode(',', $this->config->scopes),
            'state' => $state,
        ];

        return Str::finish($this->config->oauth_url, '/').'?'.http_build_query($query);
    }

    /**
     * Exchange the code for an access token
     *
     * @see https://developers.pinterest.com/docs/getting-started/authentication/#3.%20Exchange%20the%20code%20for%20an%20access%20token
     *
     * @throws RequestException
     */
    public function requestAccessToken(): array
    {
        $response = $this->service
            ->buildRequestWithBasicBase64()
            ->post(self::ENDPOINT, [
                'redirect_uri' => $this->config->oauth_redirect_uri,
                'code' => $this->oauth->access_code,
                'grant_type' => 'authorization_code',
            ])
            ->throw()
            ->json();

        event(new CredentialsRetrieved(OAuthData::fromApi($response)));

        return $response;
    }

    /**
     * Refreshing an access token
     *
     * @see https://developers.pinterest.com/docs/getting-started/authentication/#1.Refreshing%20an%20access%20token
     *
     * @throws RequestException
     */
    public function refreshAccessToken(): array
    {
        $response = $this->service
            ->buildRequestWithBasicBase64()
            ->post(self::ENDPOINT, [
                'refresh_token' => $this->oauth->refresh_token,
                'grant_type' => 'refresh_token',
            ])
            ->throw()
            ->json();

        event(new CredentialsRetrieved(OAuthData::fromApi($response)));

        return $response;
    }
}
