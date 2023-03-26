<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Data\Concerns;

trait HandlesArray
{
    use InteractsWithPublicProperties;

    public function toArray(): array
    {
        return $this->getPublicProperties()->toArray();
    }
}
