<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Data;

use Illuminate\Support\Collection;
use Lemaur\Pinterest\Data\Concerns\HandlesArray;
use Lemaur\Pinterest\Data\Concerns\HandlesCollection;
use Lemaur\Pinterest\Data\Contracts\DataContract;

abstract class Data implements DataContract
{
    use HandlesArray;
    use HandlesCollection;

    public static function from(array $data): static
    {
        return new static(...$data);
    }

    public static function collection(array $data): Collection
    {
        return Collection::make($data)->map(fn ($item) => static::from($item));
    }
}
