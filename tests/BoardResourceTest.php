<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Lemaur\Pinterest\Facades\Pinterest;
use Lemaur\Pinterest\Services\Resources\BoardResource;

it('lists all boards', function (): void {
    Pinterest::fake();

    Pinterest::board()->list();

    Pinterest::assertSentCount(1);

    Pinterest::assertSent(function (Request $request) {
        return $request->url() === $this->baseUrl.BoardResource::ENDPOINT
            && $request->method() === 'GET';
    });
});
