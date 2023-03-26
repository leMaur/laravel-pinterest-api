<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Data;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Lemaur\Pinterest\Enums\PrivacyEnum;

class BoardData extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $description,
        public readonly PrivacyEnum $privacy,
        public readonly ?string $cover_image = null,
    ) {
    }

    public static function fromApi(array $data): self
    {
        return new self(
            id: Arr::get($data, 'id'),
            name: Arr::get($data, 'name'),
            description: Arr::get($data, 'description'),
            privacy: PrivacyEnum::from(Arr::get($data, 'privacy')),
            cover_image: Arr::get($data, 'media.image_cover_url'),
        );
    }

    public static function collectionFromApi(array $data): Collection
    {
        return Collection::make($data)->map(fn ($item) => self::fromApi($item));
    }
}
