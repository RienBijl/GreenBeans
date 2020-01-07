<?php

namespace GreenBeans\Console;

use GreenBeans\Console\Commands\Brew\Provider;
use GreenBeans\Console\Commands\Router\Compile;
use GreenBeans\Console\Commands\Router\CompileWatch;
use GreenBeans\Console\Commands\Serve;
use GreenBeans\Console\Commands\Serveroot;

class KernelCommandRegistry
{

    public static array $registry = [
        // brew provider
        "brew|provider|{var}" => Provider::class,

        // serve-root
        "serve-root" => Serveroot::class,
        "serve-root|{var}" => Serveroot::class,

        // serve
        "serve" => Serve::class,
        "serve|{var}" => Serve::class,

        // routes compile
        "router|compile" => Compile::class,
        "router|compile:watch" => CompileWatch::class
    ];
}
