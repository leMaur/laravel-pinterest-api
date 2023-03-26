<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Services;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PinterestFake
{
    public function __construct(
        private readonly Pinterest $service,
        private readonly ResponseSequence|PromiseInterface|null $response = null,
    ) {
        Http::preventStrayRequests();

        Http::fake([
            Str::finish($this->service->config->base_url, '/').'*' => $this->response ?? Http::response([]),
        ]);
    }

    public function assertSent(callable $callback): void
    {
        Http::assertSent($callback);
    }

    public function assertNotSent(callable $callback): void
    {
        Http::assertNotSent($callback);
    }

    public function assertSentCount(int $count): void
    {
        Http::assertSentCount($count);
    }

    public function assertNothingSent(): void
    {
        Http::assertNothingSent();
    }

    public function __call($method, $parameters)
    {
        return $this->service->{$method}(...$parameters);
    }
}
