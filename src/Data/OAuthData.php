<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Data;

use Carbon\Carbon;
use DateInterval;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;

class OAuthData extends Data
{
    public function __construct(
        public readonly ?string $access_code = null,
        public readonly ?string $access_token = null,
        public readonly ?string $refresh_token = null,
        public readonly ?Carbon $access_token_expired_at = null,
        public readonly ?Carbon $refresh_token_expired_at = null,
    ) {
    }

    public function missingAccessToken(): bool
    {
        return blank($this->access_token);
    }

    public function isAccessTokenExpired(): bool|null
    {
        return $this->access_token_expired_at?->isPast();
    }

    public function isRefreshTokenExpired(): bool|null
    {
        return $this->refresh_token_expired_at?->isPast();
    }

    public function accessTokenExpiresIn(): DateInterval
    {
        return $this->access_token_expired_at->diff();
    }

    public function refreshTokenExpiresIn(): DateInterval
    {
        return $this->refresh_token_expired_at->diff();
    }

    public static function fromApi(array $data): self
    {
        if (($accessTokenExpiredAt = Arr::get($data, 'expires_in')) !== null) {
            $accessTokenExpiredAt = Date::now()->addSeconds((int) $accessTokenExpiredAt);
        }

        if (($refreshTokenExpiredAt = Arr::get($data, 'refresh_token_expires_in')) !== null) {
            $refreshTokenExpiredAt = Date::now()->addSeconds((int) $refreshTokenExpiredAt);
        }

        return new self(
            access_code: Arr::get($data, 'access_code'),
            access_token: Arr::get($data, 'access_token'),
            refresh_token: Arr::get($data, 'refresh_token'),
            access_token_expired_at: $accessTokenExpiredAt,
            refresh_token_expired_at: $refreshTokenExpiredAt,
        );
    }
}
