<?php

namespace GreenBeans\Console\Commands\Router;

use GreenBeans\Console\Command;
use GreenBeans\Router\RouteCompiler;
use GreenBeans\Util\ANSIColor;
use GreenBeans\Util\Stopwatch;

class Compile extends Command
{

    private const ROUTE_LOCATION = __DIR__ . '/../../../../routes_c.json';

    /**
     * @inheritDoc
     */
    public function run(array $args): void
    {
        $stopwatch = new Stopwatch();

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

                file_put_contents(self::ROUTE_LOCATION, $routesAsJson, LOCK_EX);

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
