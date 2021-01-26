<?php

namespace Core;

final class Route
{
    protected static Router $router;

    private function __construct()
    {
    }

    public static function getRouter(): Router
    {
        if (empty(self::$router)) {
            self::$router = new Router;
        }
        return self::$router;
    }

    /**
     * @param string $pattern
     * @param array|string|callable $callback
     * @return Router
     */
    public static function post(string $pattern, $callback): Router
    {
        return self::getRouter()->post($pattern, $callback);
    }

    /**
     * @param string $pattern
     * @param array|string|callable $callback
     * @return Router
     */
    public static function get(string $pattern, $callback): Router
    {
        return self::getRouter()->get($pattern, $callback);
    }

    /**
     * @param string $pattern
     * @param array|string|callable $callback
     * @return Router
     */
    public static function put(string $pattern, $callback): Router
    {
        return self::getRouter()->put($pattern, $callback);
    }

    /**
     * @param string $pattern
     * @param array|string|callable $callback
     * @return Router
     */
    public static function delete(string $pattern, $callback): Router
    {
        return self::getRouter()->delete($pattern, $callback);
    }
}
