<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Facades;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Support\Facades\Facade;
use Lemaur\Pinterest\Services\Contracts\PinterestContract;
use Lemaur\Pinterest\Services\PinterestFake;
use Lemaur\Pinterest\Services\Resources\BoardResource;
use Lemaur\Pinterest\Services\Resources\OAuthResource;
use Lemaur\Pinterest\Services\Resources\PinResource;

/**
 * @method static OAuthResource oauth()
 * @method static BoardResource board()
 * @method static PinResource pin()
 * @method static void assertSent(callable $callback)
 * @method static void assertNotSent(callable $callback)
 * @method static void assertSentCount(int $count)
 * @method static void assertNothingSent()
 *
 * @see \Lemaur\Pinterest\Services\Pinterest
 */
class Pinterest extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PinterestContract::class;
    }

    public static function fake(ResponseSequence|PromiseInterface|null $response = null): PinterestFake
    {
        static::swap($fake = new PinterestFake(static::getFacadeRoot(), $response));

        return $fake;
    }
}
