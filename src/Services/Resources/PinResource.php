<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Services\Resources;

use Illuminate\Http\Client\RequestException;
use Lemaur\Pinterest\Data\MediaSource\ImageBase64Data;
use Lemaur\Pinterest\Data\MediaSource\ImageUrlData;
use Lemaur\Pinterest\Data\MediaSource\MultipleImageBase64Data;
use Lemaur\Pinterest\Data\MediaSource\MultipleImageUrlsData;
use Lemaur\Pinterest\Data\MediaSource\VideoIdData;
use Lemaur\Pinterest\Services\Pinterest;
use OAuthException;
use RuntimeException;

class PinResource
{
    public const ENDPOINT = '/pins';

    public function __construct(
        private readonly Pinterest $service,
    ) {
    }

    public function list()
    {
        throw new RuntimeException('Not implemented yet.');
    }

    /**
     * Create a Pin on a board or board section owned by the "operation user_account"
     *
     * @ratelimit-category org_write
     *
     * @authorizations boards:read, boards:write, pins:read, pins:write
     *
     * @see https://developers.pinterest.com/docs/api/v5/#operation/pins/create
     *
     * @throws OAuthException
     * @throws RequestException
     */
    public function create(
        string $boardId,
        ImageBase64Data|ImageUrlData|VideoIdData|MultipleImageBase64Data|MultipleImageUrlsData $mediaSource,
        ?string $boardSectionId = null,
        ?string $title = null,
        ?string $description = null,
        ?string $link = null,
        ?string $dominantColor = null,
        ?string $altText = null,
        ?string $parentPinId = null,
        ?int $accountId = null
    ): array {
        $endpoint = ($accountId !== null)
            ? self::ENDPOINT.'?'.http_build_query(['ad_account_id' => $accountId])
            : self::ENDPOINT;

        return $this->service
            ->buildRequestWithToken()
            ->post($endpoint, array_filter([
                'link' => $link,
                'title' => $title,
                'description' => $description,
                'dominant_color' => $dominantColor,
                'alt_text' => $altText,
                'board_id' => $boardId,
                'board_section_id' => $boardSectionId,
                'media_source' => $mediaSource->toArray(),
                'parent_pin_id' => $parentPinId,
            ]))
            ->throw()
            ->json();
    }

    public function get(): array
    {
        throw new RuntimeException('Not implemented yet.');
    }

    public function delete(): bool
    {
        throw new RuntimeException('Not implemented yet.');
    }

    public function update(): array
    {
        throw new RuntimeException('Not implemented yet.');
    }

    public function save(): array
    {
        throw new RuntimeException('Not implemented yet.');
    }

    public function analytics(): array
    {
        throw new RuntimeException('Not implemented yet.');
    }
}
