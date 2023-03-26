<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Lemaur\Pinterest\Data\OAuthData;

class CredentialsRetrieved
{
    use Dispatchable;

    public function __construct(
        public readonly OAuthData $oauth,
    ) {
    }
}
