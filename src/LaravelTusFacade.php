<?php

namespace Clickonmedia\LaravelTus;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Clickonmedia\LaravelTus\Skeleton\SkeletonClass
 */
class LaravelTusFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-tus';
    }
}
