<?php

namespace GreenBeans\Router;

use GreenBeans\Exceptions\FileNotFoundException;

class Router
{

    /**
     * HTTP Request Method
     * @var string
     */
    private string $method;

    /**
     * Request parameters
     * @var array
     */
    private ?array $parameters = null;

    /**
     * Request URL
     * @var string
     */
    private string $url;

    /**
     * Routes in application
     * @var array
     */
    private array $routes;

    /**
     * Base url of application
     * @var string
     */
    private string $baseUrl;

    /**
     * Router constructor.
     * @param $method
     * @param $url
     */
    public function __construct($method, $url)
    {
        $this->method = $method;
        $this->url = $url;
    }

    /**
     * Load in the routes
     * @param string $path
     * @throws FileNotFoundException
     */
    public function loadRoutes(string $path)
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException("Could not find routes [" . realpath($path) . "]");
        }
        $this->routes = json_decode(file_get_contents($path), true);
    }

    /**
     * Set the base url
     * @param string $baseUrl
     * @return string
     */
    public function setBaseUrl(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * Get the real URL without meaningless information
     * @param string $url
     * @return string
     */
    public function getRealUrl(string $url): string
    {
        // Remove basepath from url
        $url = substr($url, strlen($this->baseUrl));

        // Remove GET query
        if (($strpos = strpos($url, '?')) !== false) {
            $url = substr($url, 0, $strpos);
        }
        return $url;
    }

    /**
     * Match routes
     * @return array
     */
    public function match(): ?array
    {
        $url = $this->getRealUrl($this->url);
        $lastRequestUrlChar = $url[strlen($url) - 1];

        foreach ($this->routes as $route) {
            $match = false;
            if ($route['http']['method'] !== $this->method) {
                continue;
            }
            if (isset($route['regex'][0]) && $route['regex'][0] === '@') {
                // Regex starts with a delimiter!
                echo '1';
                $pattern = '`' . substr($route, 1) . '`u';
                $match = preg_match($pattern, $url, $this->parameters) === 1;
            } elseif (($position = strpos($route["regex"], '[')) === false) {
                // Safe to use we have no parameters, string comparison will do!
                $match = strcmp(rtrim($url, '/'), rtrim($route["http"]["route"], '/')) === 0;
            } else {
                //  longest non-param string with url before regex matching
                if (!preg_match($route["regex"], $url)) {
                    continue;
                }

                $match = preg_match($route["regex"], $url, $this->parameters) === 1;
            }

            if ($match) {
                if ($this->parameters) {
                    foreach ($this->parameters as $key => $value) {
                        if (is_numeric($key)) {
                            unset($this->parameters[$key]);
                        }
                    }
                }
                $route['parameters'] = $this->parameters ?? [];
                return $route;
            }
        }
        return null;
    }
}