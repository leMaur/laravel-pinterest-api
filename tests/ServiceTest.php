<?php

declare(strict_types=1);

use Lemaur\Pinterest\Services\Contracts\PinterestContract;
use Lemaur\Pinterest\Services\Pinterest;

it('resolves pinterest service from the container', function () {

    $service = resolve(PinterestContract::class);

    expect($service)->toBeInstanceOf(Pinterest::class);
});

