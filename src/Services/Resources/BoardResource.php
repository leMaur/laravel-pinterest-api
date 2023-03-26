<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Services\Resources;

use Illuminate\Http\Client\RequestException;
use Lemaur\Pinterest\Enums\PrivacyEnum;
use Lemaur\Pinterest\Exceptions\OAuthException;
use Lemaur\Pinterest\Services\Pinterest;
use RuntimeException;

class BoardResource
{
    public const ENDPOINT = '/boards';

    public function __construct(
        private readonly Pinterest $service,
    ) {
    }

    /**
     * Get a list of the boards owned by the "operation user_account"
     *
     * @ratelimit-category org_read
     *
     * @Authorizations boards:read
     *
     * @see https://developers.pinterest.com/docs/api/v5/#operation/boards/list
     *
     * @throws RequestException
     * @throws OAuthException
     */
    public function list(?string $bookmark = null, ?string $pageSize = null, ?PrivacyEnum $privacy = null, ?int $accountId = null): array
    {
        return $this->service
            ->buildRequestWithToken()
            ->get(self::ENDPOINT, array_filter([
                'ad_account_id' => $accountId,
                'bookmark' => $bookmark,
                'page_size' => $pageSize,
                'privacy' => $privacy,
            ]))
            ->throw()
            ->json();
    }

    public function create(): array
    {
        throw new RuntimeException('Not implemented yet.');
    }

    public function get(): array
    {
        throw new RuntimeException('Not implemented yet.');
    }

    public function update(): array
    {
        throw new RuntimeException('Not implemented yet.');
    }

    public function delete(): bool
    {
        throw new RuntimeException('Not implemented yet.');
    }

    public function pins(): array
    {
        throw new RuntimeException('Not implemented yet.');
    }
}
