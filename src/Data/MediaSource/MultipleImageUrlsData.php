<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Data\MediaSource;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * @throws ValidationException
 */
class MultipleImageUrlsData extends MediaSourceData
{
    public function __construct(
        /** @var MediaUrlItemData[] $items */
        public readonly array $items,
        public readonly int $index,
    ) {
        parent::__construct(source_type: 'multiple_image_urls');

        Validator::make(
            data: ['index' => $this->index],
            rules: ['index' => ['integer', 'min:0']]
        )->validate();
    }
}
