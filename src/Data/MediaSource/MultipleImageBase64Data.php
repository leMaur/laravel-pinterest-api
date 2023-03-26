<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Data\MediaSource;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * @throws ValidationException
 */
class MultipleImageBase64Data extends MediaSourceData
{
    public function __construct(
        /** @var MediaBase64ItemData[] $items */
        public readonly array $items,
        public readonly int $index,
    ) {
        parent::__construct(source_type: 'multiple_image_base64');

        Validator::make(
            data: ['index' => $this->index],
            rules: ['index' => ['integer', 'min:0']]
        )->validate();
    }
}
