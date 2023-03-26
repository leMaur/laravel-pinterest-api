<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Tests;

use Lemaur\Pinterest\PinterestServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            PinterestServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        //
    }
}
