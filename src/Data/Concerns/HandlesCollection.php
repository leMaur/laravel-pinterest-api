<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Data\Concerns;

use Illuminate\Support\Collection;

trait HandlesCollection
{
    use InteractsWithPublicProperties;

    public function toCollection(): Collection
    {
        return $this->getPublicProperties();
    }
}
