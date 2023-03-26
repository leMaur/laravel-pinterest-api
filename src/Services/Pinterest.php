<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Services;

use Lemaur\Pinterest\Data\ConfigData;
use Lemaur\Pinterest\Data\OAuthData;
use Lemaur\Pinterest\Exceptions\OAuthException;
use Lemaur\Pinterest\Services\Concerns\BuildBaseRequest;
use Lemaur\Pinterest\Services\Contracts\PinterestContract;
use Lemaur\Pinterest\Services\Resources\BoardResource;
use Lemaur\Pinterest\Services\Resources\OAuthResource;
use Lemaur\Pinterest\Services\Resources\PinResource;

class Pinterest implements PinterestContract
{
    use BuildBaseRequest;

    public function __construct(
        public readonly ConfigData $config,
        public readonly OAuthData $oauth,
    ) {
    }

    /**
     * @throws OAuthException
     */
    public function oauth(): OAuthResource
    {
        return new OAuthResource(
            service: $this,
            config: $this->config,
            oauth: $this->oauth
        );
    }

    public function board(): BoardResource
    {
        return new BoardResource(
            service: $this
        );
    }

    public function pin(): PinResource
    {
        return new PinResource(
            service: $this
        );
    }
}
