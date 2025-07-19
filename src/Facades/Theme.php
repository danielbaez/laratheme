<?php

namespace DanielBaez\LaraTheme\Facades;

use Illuminate\Support\Facades\Facade;

/*class Theme
{
    protected static function getFacadeAccessor(): string
    {
        return 'laratheme';
    }

    public static function __callStatic($method, $args)
    {
        return app(self::getFacadeAccessor())->$method(...$args);
    }
}*/

class Theme extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laratheme';
    }
}