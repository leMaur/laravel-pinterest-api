<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Http\Responses\Contracts;

use Illuminate\Http\Response;

interface CallbackResponseContract
{
    public function __invoke(string $accessCode, string $state, ?string $internalState): Response;
}
