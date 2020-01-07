<?php

namespace GreenBeans\Router;

use GreenBeans\Exceptions\RouterException;
use GreenBeans\Util\StringUtil;

class RouteCompiler
{

    private array $filesToScan = [];
    private array $routes = [];


    protected array $matchTypes = [
        'i'  => '[0-9]++',
        'a'  => '[0-9A-Za-z]++',
        'h'  => '[0-9A-Fa-f]++',
        '*'  => '.+?',
        '**' => '.++',
        ''   => '[^/\.]++'
    ];

    public const BASE_DIR = __DIR__ . "/../../App/Controllers/";

    /**
     * RouteCompiler constructor.
     */
    public function __construct()
    {
        $this->filesToScan = $this->getEligibleFiles();
        foreach ($this->filesToScan as $fileToScan) {
            $this->runTokenizer($fileToScan);
        }
    }

    /**
     * Get the routes that were compiled
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes ?? [];
    }


    /**
     * Run the tokenizer and store the routes
     * @param string $fileToScan
     * @throws RouterException
     */
    private function runTokenizer(string $fileToScan): void
    {
        $path = realpath(self::BASE_DIR . '/' . $fileToScan);
        $file = preg_replace('/\\.[^.\\s]{3,4}$/', '', $fileToScan);
        $routes = (new RoutesTokenizer(file_get_contents($path)))->getResults();
        foreach ($routes as $route) {
            $id = strtolower($route["http"]["method"] . ':' . $route["http"]["route"]);

            if (isset($this->routes[$id])) {
                $sFile = $this->routes[$id]["file"];
                $desc = $route["http"]["method"] . ' ' . $route["http"]["route"] . ' in ' . $file . ' and ' . $sFile;
                throw new RouterException("Duplicate route declaration (" . $desc . ')');
            } else {
                $route["file"] = $file;
                $route["regex"] = $this->compileRoute($route["http"]["route"]);
                $this->routes[$id] = $route;
            }
        }
    }

    /**
     * Turn a route into a regex
     * @param string $route
     * @return string
     */
    protected function compileRoute(string $route): string
    {
        if (preg_match_all('`(/|\.|)\[([^:\]]*+)(?::([^:\]]*+))?\](\?|)`', $route, $matches, PREG_SET_ORDER)) {
            $matchTypes = $this->matchTypes;
            foreach ($matches as $match) {
                list($block, $pre, $type, $param, $optional) = $match;
                if (isset($matchTypes[$type])) {
                    $type = $matchTypes[$type];
                }
                if ($pre === '.') {
                    $pre = '\.';
                }
                $optional = $optional !== '' ? '?' : null;

                $pattern = '(?:'
                    . ($pre !== '' ? $pre : null)
                    . '('
                    . ($param !== '' ? "?P<$param>" : null)
                    . $type
                    . ')'
                    . $optional
                    . ')'
                    . $optional;
                $route = str_replace($block, $pattern, $route);
            }
        }
        return "`^$route$`u";
    }

    /**
     * Get all the eligible files
     * @return array
     */
    private function getEligibleFiles(): array
    {
        $files = scandir(self::BASE_DIR);
        $eligible = [];
        foreach ($files as $file) {
            if (StringUtil::endsWith($file, ".php")) {
                $eligible[] = $file;
            }
        }
        return $eligible;
    }

}