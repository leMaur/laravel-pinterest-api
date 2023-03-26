<?php

declare(strict_types=1);

namespace Lemaur\Pinterest\Tests;

use Lemaur\Pinterest\Data\ConfigData;
use Lemaur\Pinterest\Data\OAuthData;
use Lemaur\Pinterest\PinterestServiceProvider;
use Lemaur\Pinterest\Services\Contracts\PinterestContract;
use Lemaur\Pinterest\Services\Pinterest;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public $baseUrl = '';

    protected function setUp(): void
    {
        parent::setUp();

        $this->baseUrl = trim(config('pinterest.base_url'), '/');
    }

    protected function overrideApplicationBindings($app)
    {
        return [
            PinterestContract::class => fn () => new Pinterest(
                config: ConfigData::fromConfig(require base_path('/../../../../config/pinterest.php')),
                oauth: OAuthData::from([
                    'access_code' => 'access-code',
                    'access_token' => 'access-token',
                    'refresh_token' => 'refresh-token',
                    'access_token_expired_at' => now()->addMonth(),
                    'refresh_token_expired_at' => now()->addYear(),
                ]),
            ),
        ];
    }

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
