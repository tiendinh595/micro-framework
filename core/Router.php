<?php
namespace core;

/**
 * Class Router
 * @package core
 */
class Router
{
    private $routes = [];
    private $notFound = null;
    private $current_router = null;
    private $group = null;

    public function __construct()
    {
        $this->notFound = function ($url) {
            throw new \Exception("<br><b>404</b> {$url} not found", 1);
        };
    }

    function get($url, $action)
    {
        $this->addRoute($url, $action, 'GET');
        return $this;
    }

    function post($url, $action)
    {
        $this->addRoute($url, $action, 'POST');
        return $this;
    }

    function any($url, $action)
    {
        $this->addRoute($url, $action, 'ANY');
        return $this;
    }

    /**
     * @param $url
     * @param $action
     * @param $method
     */
    function addRoute($url, $action, $method)
    {
        $route = is_array($url) ? $url : ['url' => $url, 'middleware' => ''];

        if($this->group != null)
            $route['url'] = $this->group.'/'.trim($route['url'], '/');

        $this->routes[$route['url']] = [
            'url' => $this->parseUrl($route['url']),
            'action' => $action,
            'method' => $method,
            'middleware' => $route['middleware']
        ];
        $this->current_router = $route['url'];
    }

    /**
     * @param string $middleware
     * @return void
     */
    public function middleware($middleware)
    {
        if(isset($this->routes[$this->current_router]))
            $this->routes[$this->current_router]['middleware'] = $middleware;
        $this->current_router = null;
    }

    /**
     * @param string $group
     * @param \Closure $closure
     * @return $this
     */
    public function group($group, \Closure $closure)
    {
        $this->group = $group;
        $closure();
        $this->group = null;
        return $this;
    }

    /**
     * @param $url
     * @return mixed|string
     */
    public static function parseUrl($url)
    {
        $url = preg_replace("/\{([a-zA-Z\d]+)\}/", "(.+)", $url);
        $url = str_replace('/', '\/', $url);
        $url = rtrim($url, '/');
        return $url;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function dispath()
    {
        // remove params get in uri
        $curent_uri = $_SERVER['REQUEST_URI'];
        $curent_uri = preg_replace("/(\?|&).+/", "", $curent_uri);

        if (isset($this->routes[$curent_uri])) {
            return $this->executeMethod($this->routes[$curent_uri]['action'], [], $this->routes[$curent_uri]['middleware']);
        }

        foreach ($this->routes as $route) {
            //compare curent url with rule in array routes
            @preg_match('/' . $route['url'] . '/', $curent_uri, $args);
            if (count($args)) {

                if (!$this->validateMethodRequest($route['method']))
                    throw new \Exception("Not allow method {$_SERVER['REQUEST_METHOD']}");

                unset($args[0]);
                foreach ($args as $param) { //check item param is valid (xx/xx is invalid, xx is valid)
                    if (count(explode('/', $param)) > 1) {
                        call_user_func_array($this->notFound, [$curent_uri]);
                    }
                }
                return $this->executeMethod($route['action'], $args);
            }
        }

        return call_user_func_array($this->notFound, [$curent_uri]);
    }

    private function validateMethodRequest($method)
    {
        return ($_SERVER['REQUEST_METHOD'] == $method || $_SERVER['REQUEST_METHOD'] == 'ANY');
    }

    /**
     * @param $action
     * @param array $args
     * @return mixed|null
     * @throws \Exception
     */
    private function executeMethod($action, $args = [], $middleware)
    {
        //check action is a closure function
        if (is_callable($action)) {
            if ($middleware != '') {
                return Middleware::executeMiddleware($middleware, function () use ($action, $args) {
                    call_user_func_array($action, $args);
                });
            }
            return call_user_func_array($action, $args);
        }

        $params = explode('@', $action);
        if (count($params) == 2) {
            $className = 'app\\Controllers\\' . $params[0];
            $object = new $className;
            if (method_exists($object, $params[1])) {
                if ($middleware != '') {
                    return Middleware::executeMiddleware($middleware, function () use ($object, $params, $args) {
                        call_user_func_array([$object, $params[1]], $args);
                    });
                }
                return call_user_func_array([$object, $params[1]], $args);
            } else {
                throw new \Exception("Method {$params[1]} not exists");
            }
        }
        return null;
    }

}