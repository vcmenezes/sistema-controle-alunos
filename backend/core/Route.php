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

    public static function post($pattern, $callback): Router
    {
        return self::getRouter()->post($pattern, $callback);
    }

    public static function get($pattern, $callback): Router
    {
        return self::getRouter()->get($pattern, $callback);
    }

    public static function put($pattern, $callback): Router
    {
        return self::getRouter()->put($pattern, $callback);
    }

    public static function delete($pattern, $callback): Router
    {
        return self::getRouter()->delete($pattern, $callback);
    }
}
