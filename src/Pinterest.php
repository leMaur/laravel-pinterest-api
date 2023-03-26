<?php

declare(strict_types=1);

namespace Lemaur\Pinterest;

use Lemaur\Pinterest\Http\Responses\Contracts\CallbackResponseContract;

class Pinterest
{
    /**
     * Register a class / callback that should be used as a response for the Pinterest callback.
     *
     * @return void
     */
    public static function callbackResponseUsing(string $callback)
    {
        app()->singleton(CallbackResponseContract::class, $callback);
    }
}
