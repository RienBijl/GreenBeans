<?php

namespace GreenBeans\Console\Commands\Router;

use GreenBeans\Console\Command;
use GreenBeans\Router\RouteCompiler;
use GreenBeans\Util\ANSIColor;
use GreenBeans\Util\Base;
use GreenBeans\Util\Stopwatch;

class Compile extends Command
{

    private string $routeLocation;

    /**
     * @inheritDoc
     */
    public function run(array $args): void
    {
        $stopwatch = new Stopwatch();
        $this->routeLocation = Base::get() . '/routes_c.json';
        $routeCompiler = null;

        try {
            $routeCompiler = new RouteCompiler();
        } catch (\Exception $ex) {
            $this->error('Error whilst trying to compile routes after ' . $stopwatch->stopAsMilli() . 'ms');
            $this->msg(ANSIColor::parse($ex, ANSIColor::FG_WHITE, ANSIColor::BG_CYAN));
        } finally {
            if ($routeCompiler !== null && ($routes = $routeCompiler->getRoutes()) !== null) {
                $routes = $routes ?? [];
                $routesAsJson = json_encode($routes, JSON_PRETTY_PRINT);

                file_put_contents($this->routeLocation, $routesAsJson, LOCK_EX);

                $this->success('Renewed route declarations in ' . $stopwatch->stopAsMilli() . 'ms');
            }
        }
    }

    /**
     * @inheritDoc
     */
    public static function invoke(array $args): void
    {
        (new self())->run($args);
    }
}
