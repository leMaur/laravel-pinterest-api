<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Data\MediaSource;

class VideoIdData extends MediaSourceData
{
    public function __construct(
        public readonly string $cover_image_url,
        public readonly string $media_id,
    ) {
        parent::__construct(source_type: 'video_id');
    }
}
