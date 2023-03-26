<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Data\MediaSource;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Lemaur\Pinterest\Enums\ContentTypeEnum;

/**
 * @throws ValidationException
 */
class ImageBase64Data extends MediaSourceData
{
    public function __construct(
        public readonly ContentTypeEnum $content_type,
        public readonly string $data,
    ) {
        parent::__construct(source_type: 'image_base64');

        Validator::make(
            data: ['data' => $this->data],
            rules: ['data' => 'regex:/[a-zA-Z0-9+\/=]+/i']
        )->validate();
    }
}
