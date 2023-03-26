<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Data;

use Illuminate\Support\Arr;

class ConfigData extends Data
{
    public function __construct(
        public readonly string $oauth_url,
        public readonly string $oauth_redirect_uri,
        public readonly string $client_id,
        public readonly string $client_secret,
        public readonly string $base_url,
        public readonly int $timeout,
        public readonly int $connect_timeout,
        public readonly bool $retry_enabled,
        public readonly int $retry_times,
        public readonly int $retry_sleep,
        public readonly array $scopes
    ) {
    }

    public static function fromConfig(array $data): self
    {
        return new self(
            oauth_url: Arr::get($data, 'oauth_url'),
            oauth_redirect_uri: Arr::get($data, 'redirect_uri'),
            client_id: Arr::get($data, 'client_id'),
            client_secret: Arr::get($data, 'client_secret'),
            base_url: Arr::get($data, 'base_url'),
            timeout: Arr::get($data, 'timeout'),
            connect_timeout: Arr::get($data, 'connect_timeout'),
            retry_enabled: Arr::get($data, 'retry.enabled'),
            retry_times: Arr::get($data, 'retry.times'),
            retry_sleep: Arr::get($data, 'retry.sleep'),
            scopes: Arr::get($data, 'scopes')
        );
    }
}
