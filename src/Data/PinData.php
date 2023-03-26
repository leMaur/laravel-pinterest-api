<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Data;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class PinData extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly string $board_id,
        public readonly string $title,
        public readonly string $description,
        public readonly string $link,
    ) {
    }

    public static function fromApi(array $data): self
    {
        return new self(
            id: Arr::get($data, 'id'),
            board_id: Arr::get($data, 'board_id'),
            title: Arr::get($data, 'title'),
            description: Arr::get($data, 'description'),
            link: Arr::get($data, 'link'),
        );
    }

    public static function collectionFromApi(array $data): Collection
    {
        return Collection::make($data)->map(fn ($item) => self::fromApi($item));
    }
}
