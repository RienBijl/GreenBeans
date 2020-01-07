<?php

namespace GreenBeans\Base;

class Provider
{

    private static $boot = false;

    public static function boot()
    {
        if (!self::$boot) {
            self::$boot = true;
            (new static())->register();
        }
    }

}