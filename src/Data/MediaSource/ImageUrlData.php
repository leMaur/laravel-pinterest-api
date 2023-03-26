<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Data\MediaSource;

class ImageUrlData extends MediaSourceData
{
    public function __construct(
        public readonly string $url,
    ) {
        parent::__construct(source_type: 'image_url');
    }
}
