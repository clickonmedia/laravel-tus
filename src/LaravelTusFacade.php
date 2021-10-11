<?php

namespace Clickonmedia\LaravelTus;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Clickonmedia\LaravelTus\LaravelTus
 */
class LaravelTusFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-tus';
    }
}
