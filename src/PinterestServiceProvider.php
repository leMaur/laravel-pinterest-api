<?php

declare(strict_types=1);

namespace Lemaur\Pinterest;

use Lemaur\Pinterest\Commands\PinterestGetAccessCodeLinkCommand;
use Lemaur\Pinterest\Http\Responses\CallbackResponse;
use Lemaur\Pinterest\Http\Responses\Contracts\CallbackResponseContract;
use Lemaur\Pinterest\Services\Resources\Contracts\OAuthResourceContract;
use Lemaur\Pinterest\Services\Resources\OAuthResource;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PinterestServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-pinterest-api')
            ->hasConfigFile('pinterest')
            ->hasCommand(PinterestGetAccessCodeLinkCommand::class)
            ->hasInstallCommand(fn (InstallCommand $command) => $command
                ->copyAndRegisterServiceProviderInApp()
                ->publishConfigFile()
                ->askToStarRepoOnGitHub('lemaur/laravel-pinterest-api'))
            ->publishesServiceProvider('PinterestServiceProvider')
            ->hasRoute('pinterest');
    }

    public function packageRegistered(): void
    {
        parent::packageRegistered();

        $this->app->singleton(OAuthResourceContract::class, OAuthResource::class);
        $this->app->singleton(CallbackResponseContract::class, CallbackResponse::class);
    }
}
