<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Services\Resources\Contracts;

use Illuminate\Http\Client\RequestException;

/**
 * Pinterest's authentication flow
 *
 * @see https://developers.pinterest.com/docs/getting-started/authentication/
 */
interface OAuthResourceContract
{
    /**
     * Request user access and receive the access code with your redirect URI
     *
     * @see https://developers.pinterest.com/docs/getting-started/authentication/#Generating%20an%20access%20token
     */
    public function getAccessCodeLink(): string;

    /**
     * Exchange the code for an access token
     *
     * @see https://developers.pinterest.com/docs/getting-started/authentication/#3.%20Exchange%20the%20code%20for%20an%20access%20token
     *
     * @throws RequestException
     */
    public function requestAccessToken(): array;

    /**
     * Refreshing an access token
     *
     * @see https://developers.pinterest.com/docs/getting-started/authentication/#1.Refreshing%20an%20access%20token
     *
     * @throws RequestException
     */
    public function refreshAccessToken(): array;
}
