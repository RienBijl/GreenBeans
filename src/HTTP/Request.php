<?php

namespace GreenBeans\HTTP;

use GreenBeans\Exceptions\FileNotFoundException;
use GreenBeans\Router\Router;
use GreenBeans\Util\Base;

class Request
{

    private ?array $route;
    private string $method;
    private string $url;
    private Response $response;

    /**
     * Request constructor.
     * @param string $method
     * @param string $url
     * @throws FileNotFoundException
     */
    private function __construct(string $method, string $url)
    {
        $this->method = $method;
        $this->url = $url;
        $this->route();

        if ($this->checkExistence()) {

        } else {
            $this->response = new Response(404);
        }
    }

    private function callController(): Con {

    }

    /**
     * Run the router and get the required route
     * @throws FileNotFoundException
     */
    private function route()
    {
        $router = new Router($this->method, $this->url);
        $router->loadRoutes(Base::get() . '/routes_c.json');
        $router->setBaseUrl($_SERVER["HTTP_HOST"]);
        $this->route = $router->match();
    }

    /**
     * If a route exists
     * @return bool
     */
    private function checkExistence(): bool
    {
        return $this->route !== null;
    }

    /**
     * Extract the request
     * @return static
     * @throws FileNotFoundException
     */
    public static function extract(): self
    {
        $method = $_SERVER["REQUEST_METHOD"] ?? "GET";
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        return new static($method, $url);
    }

    /**
     * Grab a get parameter
     * @param $parameter
     * @return string|null
     */
    public function get(string $parameter): ?string
    {
        return isset($_GET[$parameter]) ? $_GET[$parameter] : null;
    }

    /**
     * Grab a post parameter
     * @param $parameter
     * @return string|null
     */
    public function post(string $parameter): ?string
    {
        return isset($_POST[$parameter]) ? $_POST[$parameter] : null;
    }

    /**
     * If has a parameter
     * @param $parameter
     * @return bool
     */
    public function has(string $parameter): bool
    {
        return isset($_POST[$parameter]) || isset($_GET[$parameter]);
    }

    /**
     * If has a get parameter
     * @param $parameter
     * @return bool
     */
    public function hasGet(string $parameter): bool
    {
        return isset($_GET[$parameter]);
    }

    /**
     * If has a post parameter
     * @param $paramater
     * @return bool
     */
    public function hasPost(string $paramater): bool
    {
        return isset($_POST[$paramater]);
    }

    /**
     * Grab a parameter
     * @param $parameter
     * @return string|null
     */
    public function param(string $parameter): ?string
    {
        if (isset($_GET[$parameter])) {
            return $_GET[$parameter];
        } elseif (isset($_POST[$parameter])) {
            return $_POST[$parameter];
        }
        return null;
    }

}