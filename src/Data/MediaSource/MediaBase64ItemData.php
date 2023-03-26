<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Data\MediaSource;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Lemaur\Pinterest\Data\Data;
use Lemaur\Pinterest\Enums\ContentTypeEnum;

/**
 * @throws ValidationException
 */
class MediaBase64ItemData extends Data
{
    public function __construct(
        public readonly ContentTypeEnum $content_type,
        public readonly string $data,
        public readonly ?string $title = null,
        public readonly ?string $description = null,
        public readonly ?string $link = null,
    ) {
        Validator::make(
            data: ['data' => $this->data],
            rules: ['data' => 'regex:[a-zA-Z0-9+\/=]+']
        )->validate();
    }
}
