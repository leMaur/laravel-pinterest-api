<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Data\MediaSource;

use Lemaur\Pinterest\Data\Concerns\HandlesArray;
use Lemaur\Pinterest\Data\Concerns\HandlesCollection;
use Lemaur\Pinterest\Data\Contracts\MediaSourceContract;

abstract class MediaSourceData implements MediaSourceContract
{
    use HandlesArray;
    use HandlesCollection;

    public function __construct(
        public string $source_type,
    ) {
    }
}
