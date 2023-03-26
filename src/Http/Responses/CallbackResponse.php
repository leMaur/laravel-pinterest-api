<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Http\Responses;

use Illuminate\Http\Response;
use Lemaur\Pinterest\Data\OAuthData;
use Lemaur\Pinterest\Events\CredentialsRetrieved;
use Lemaur\Pinterest\Http\Responses\Contracts\CallbackResponseContract;

class CallbackResponse implements CallbackResponseContract
{
    public function __invoke(string $accessCode, string $state, ?string $internalState): Response
    {
        if ($internalState === null) {
            return new Response(
                content: 'Your request has expired! Please try again by running `php artisan pinterest:generate-access-code-link`.',
                status: Response::HTTP_REQUEST_TIMEOUT
            );
        }

        if ($state !== $internalState) {
            return new Response(
                content: 'Not good! Request has been tampered!',
                status: Response::HTTP_FORBIDDEN
            );
        }

        event(new CredentialsRetrieved(OAuthData::from(['access_code' => $accessCode])));

        return new Response(
            content: 'All good! You can close this page.',
            status: Response::HTTP_OK
        );
    }
}
