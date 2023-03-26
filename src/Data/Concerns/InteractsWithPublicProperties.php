<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Data\Concerns;

use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionProperty;

trait InteractsWithPublicProperties
{
    private function getPublicProperties(): Collection
    {
        return collect(json_decode(
            json: collect((new ReflectionClass(static::class))->getProperties(ReflectionProperty::IS_PUBLIC))
                ->mapWithKeys(fn(ReflectionProperty $property): array => [
                    $property->getName() => $this->{$property->getName()},
                ])->toJson(),
            associative: true
        ));
    }
}
