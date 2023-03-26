<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Lemaur\Pinterest\Data\MediaSource\ImageBase64Data;
use Lemaur\Pinterest\Data\MediaSource\ImageUrlData;
use Lemaur\Pinterest\Data\MediaSource\MediaBase64ItemData;
use Lemaur\Pinterest\Data\MediaSource\MediaUrlItemData;
use Lemaur\Pinterest\Data\MediaSource\MultipleImageBase64Data;
use Lemaur\Pinterest\Data\MediaSource\MultipleImageUrlsData;
use Lemaur\Pinterest\Data\MediaSource\VideoIdData;
use Lemaur\Pinterest\Enums\ContentTypeEnum;
use Lemaur\Pinterest\Facades\Pinterest;
use Lemaur\Pinterest\Services\Resources\PinResource;

it('creates pin with image base 64 media source', function (): void {
    Pinterest::fake();

    $data = base64_encode('valid-base64-image-data');

    Pinterest::pin()->create(
        boardId: 'abc123',
        mediaSource: new ImageBase64Data(
            content_type: ContentTypeEnum::JPEG,
            data: $data,
        ),
    );

    Pinterest::assertSentCount(1);

    Pinterest::assertSent(function (Request $request) use ($data) {
        return $request->url() === $this->baseUrl.PinResource::ENDPOINT
                && $request->method() === 'POST'
            && $request->data()['board_id'] === 'abc123'
            && $request->data()['media_source'] === [
                'content_type' => 'image/jpeg',
                'data' => $data,
                'source_type' => 'image_base64',
            ];
    });
});

it('creates pin with image url media source', function (): void {
    Pinterest::fake();

    Pinterest::pin()->create(
        boardId: 'abc123',
        mediaSource: new ImageUrlData(
            url: 'valid-image-url',
        ),
    );

    Pinterest::assertSentCount(1);

    Pinterest::assertSent(function (Request $request) {
        return $request->url() === $this->baseUrl.PinResource::ENDPOINT
            && $request->method() === 'POST'
            && $request->data()['board_id'] === 'abc123'
            && $request->data()['media_source'] === [
                'url' => 'valid-image-url',
                'source_type' => 'image_url',
            ];
    });
});

it('creates pin with video id media source', function (): void {
    Pinterest::fake();

    Pinterest::pin()->create(
        boardId: 'abc123',
        mediaSource: new VideoIdData(
            cover_image_url: 'valid-cover-image-url',
            media_id: 'valid-media-id',
        ),
    );

    Pinterest::assertSentCount(1);

    Pinterest::assertSent(function (Request $request) {
        return $request->url() === $this->baseUrl.PinResource::ENDPOINT
            && $request->method() === 'POST'
            && $request->data()['board_id'] === 'abc123'
            && $request->data()['media_source'] === [
                'cover_image_url' => 'valid-cover-image-url',
                'media_id' => 'valid-media-id',
                'source_type' => 'video_id',
            ];
    });
});

it('creates pin with multiple image base 64 media source', function (): void {
    Pinterest::fake();

    $data = [];

    Pinterest::pin()->create(
        boardId: 'abc123',
        mediaSource: new MultipleImageBase64Data(
            items: [
                new MediaBase64ItemData(
                    content_type: ContentTypeEnum::JPEG,
                    data: $data[] = base64_encode('valid-base64-image-data-1'),
                ),
                new MediaBase64ItemData(
                    content_type: ContentTypeEnum::JPEG,
                    data: $data[] = base64_encode('valid-base64-image-data-2'),
                ),
            ],
            index: 2,
        ),
    );

    Pinterest::assertSentCount(1);

    Pinterest::assertSent(function (Request $request) use ($data) {
        return $request->url() === $this->baseUrl.PinResource::ENDPOINT
            && $request->method() === 'POST'
            && $request->data()['board_id'] === 'abc123'
            && $request->data()['media_source'] === [
                'items' => [
                    [
                        'content_type' => 'image/jpeg',
                        'data' => $data[0],
                        'title' => null,
                        'description' => null,
                        'link' => null,
                    ],
                    [
                        'content_type' => 'image/jpeg',
                        'data' => $data[1],
                        'title' => null,
                        'description' => null,
                        'link' => null,
                    ],
                ],
                'index' => 2,
                'source_type' => 'multiple_image_base64',
            ];
    });
});

it('creates pin with multiple image url media source', function (): void {
    Pinterest::fake();

    $url = [];

    Pinterest::pin()->create(
        boardId: 'abc123',
        mediaSource: new MultipleImageUrlsData(
            items: [
                new MediaUrlItemData(
                    url: $url[] = 'valid-image-url-1',
                ),
                new MediaUrlItemData(
                    url: $url[] = 'valid-image-url-2',
                ),
            ],
            index: 2,
        ),
    );

    Pinterest::assertSentCount(1);

    Pinterest::assertSent(function (Request $request) use ($url) {
        return $request->url() === $this->baseUrl.PinResource::ENDPOINT
            && $request->method() === 'POST'
            && $request->data()['board_id'] === 'abc123'
            && $request->data()['media_source'] === [
                'items' => [
                    [
                        'url' => $url[0],
                        'title' => null,
                        'description' => null,
                        'link' => null,
                    ],
                    [
                        'url' => $url[1],
                        'title' => null,
                        'description' => null,
                        'link' => null,
                    ],
                ],
                'index' => 2,
                'source_type' => 'multiple_image_urls',
            ];
    });
});
