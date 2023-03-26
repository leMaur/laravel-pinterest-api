<?php

declare(strict_types=1);

namespace Lemaur\Pinterest;

use Lemaur\Pinterest\Http\Responses\CallbackResponse;
use Lemaur\Pinterest\Http\Responses\Contracts\CallbackResponseContract;
use Lemaur\Pinterest\Services\Resources\Contracts\OAuthResourceContract;
use Lemaur\Pinterest\Services\Resources\OAuthResource;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PinterestServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-pinterest-api')
            ->hasConfigFile('pinterest')
            ->publishesServiceProvider('PinterestServiceProvider')
            ->hasRoute('pinterest');
    }

    public function packageRegistered(): void
    {
        parent::packageRegistered();

        $this->app->singleton(OAuthResourceContract::class, OAuthResource::class);
        $this->app->singleton(CallbackResponseContract::class, CallbackResponse::class);
    }

    public function packageBooted(): void
    {
        parent::packageBooted();

        $this->publishes([
            $this->package->basePath('/../resources/stubs/PinterestGetAccessCodeLink.php.stub') => base_path('app/Console/Commands/PinterestGetAccessCodeLink.php'),
        ], "{$this->package->shortName()}-support");
    }
}
