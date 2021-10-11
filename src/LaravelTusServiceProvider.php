<?php

namespace Clickonmedia\LaravelTus;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Clickonmedia\LaravelTus\Commands\LaravelTusCommand;

class LaravelTusServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-tus')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-tus_table')
            ->hasCommand(LaravelTusCommand::class);
    }
}
