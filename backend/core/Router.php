<?php

namespace Core;

use Exception;
use RuntimeException;
use stdClass;

class Router
{
    protected ?stdClass $route;

    public function __construct()
    {
        $route = null;
    }

    public function post($pattern, $callback): Router
    {
        $this->add('post', $pattern, $callback, null);
        return $this;
    }

    public function get($pattern, $callback): Router
    {
        $this->add('get', $pattern, $callback, null);
        return $this;
    }

    public function put($pattern, $callback): Router
    {
        $this->add('put', $pattern, $callback);
        return $this;
    }

    public function delete($pattern, $callback): Router
    {
        $this->add('delete', $pattern, $callback);
        return $this;
    }

    public function add($method, $pattern, $callback, $name = null): void
    {
        RouteCollection::add($method, $pattern, $callback, $name);
    }

    public function find($method, Request $request): ?Router
    {
        $find = RouteCollection::find($method, $request->uri());

        if ($find) {
            $this->route = $find;
            return $this;
        }
        return $this->route = null;
    }

    public function dispatch()
    {
        if ($this->route === null) {
            return null;
        }

        try {
            if (is_callable($this->route->callback)) {
                return call_user_func_array($this->route->callback, array_values($this->route->params));
            }

            $call = explode("@", $this->route->callback);

            if (count($call) === 2) {
                $controller = "App\\Controllers\\" . $call[0];
                $controller = new $controller;
                $method = $call[1];
                return call_user_func_array(array($controller, $method), array_values($this->route->params));
            }

            throw new RuntimeException("Declaração de rota incorreta");
        } catch (Exception $e) {
            throw $e;
        }
    }
}
