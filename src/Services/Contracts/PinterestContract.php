<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Services\Contracts;

use Lemaur\Pinterest\Services\Resources\BoardResource;
use Lemaur\Pinterest\Services\Resources\OAuthResource;
use Lemaur\Pinterest\Services\Resources\PinResource;

interface PinterestContract
{
    public function oauth(): OAuthResource;

    public function board(): BoardResource;

    public function pin(): PinResource;
}
