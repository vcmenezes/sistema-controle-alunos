<?php

namespace Core;

use Exception;
use RuntimeException;
use stdClass;

class Router
{
    protected ?stdClass $route = null;

    public function __construct()
    {
    }

    /**
     * @param string $pattern
     * @param array|string|callable $callback
     * @return Router
     */
    public function post(string $pattern, $callback): Router
    {
        $this->add('post', $pattern, $callback);
        return $this;
    }

    /**
     * @param string $pattern
     * @param array|string|callable $callback
     * @return Router
     */
    public function get(string $pattern, $callback): Router
    {
        $this->add('get', $pattern, $callback);
        return $this;
    }

    /**
     * @param string $pattern
     * @param array|string|callable $callback
     * @return Router
     */
    public function put(string $pattern, $callback): Router
    {
        $this->add('put', $pattern, $callback);
        return $this;
    }

    /**
     * @param string $pattern
     * @param array|string|callable $callback
     * @return Router
     */
    public function delete(string $pattern, $callback): Router
    {
        $this->add('delete', $pattern, $callback);
        return $this;
    }

    /**
     * @param string $method
     * @param string $pattern
     * @param array|string|callable $callback
     */
    public function add(string $method, string $pattern, $callback): void
    {
        RouteCollection::add($method, $pattern, $callback);
    }

    public function find(string $method, Request $request): ?Router
    {
        $find = RouteCollection::find($method, $request->uri());

        if ($find) {
            $this->route = $find;
            return $this;
        }
        return $this->route = null;
    }

    /**
     * @return false|mixed|null
     */
    public function dispatch()
    {
        if ($this->route === null) {
            return null;
        }

        try {
            if (is_array($this->route->callback)) {
                $controller = $this->route->callback[0];
                $controller = new $controller;
                $method = $this->route->callback[1];
                return call_user_func_array(array($controller, $method), array_values($this->route->params));
            }

            if (is_callable($this->route->callback)) {
                return call_user_func_array($this->route->callback, array_values($this->route->params));
            }

            if (is_string($this->route->callback)) {
                $call = explode("@", $this->route->callback);
                if (count($call) === 2) {
                    $controller = "App\\Controllers\\" . $call[0];
                    $controller = new $controller;
                    $method = $call[1];
                    return call_user_func_array(array($controller, $method), array_values($this->route->params));
                }
            }

            throw new RuntimeException("Declaração de rota incorreta");
        } catch (Exception $e) {
            throw $e;
        }
    }
}
