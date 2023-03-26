<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Data\Contracts;

use Illuminate\Support\Collection;

interface DataContract
{
    public function toCollection(): Collection;

    public function toArray(): array;
}
