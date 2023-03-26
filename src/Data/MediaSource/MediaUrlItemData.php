<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Data\MediaSource;

use Lemaur\Pinterest\Data\Data;

class MediaUrlItemData extends Data
{
    public function __construct(
        public readonly string $url,
        public readonly ?string $title = null,
        public readonly ?string $description = null,
        public readonly ?string $link = null,
    ) {
    }
}
