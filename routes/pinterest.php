<?php

declare(strict_types=1);

use Lemaur\Pinterest\Http\Controllers\CallbackController;

Route::get(
    uri: config('pinterest.route.endpoint', 'pinterest/callback'),
    action: CallbackController::class
)->middleware(
    middleware: config('pinterest.route.middleware', [])
);
