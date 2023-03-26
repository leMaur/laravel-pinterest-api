<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Lemaur\Pinterest\Http\Responses\Contracts\CallbackResponseContract;

class CallbackController
{
    public function __invoke(Request $request): Response
    {
        return app(CallbackResponseContract::class)(
            accessCode: (string) $request->query('code'),
            state: $request->query('state'),
            internalState: Cache::pull('pinterest_api::oauth_state')
        );
    }
}
