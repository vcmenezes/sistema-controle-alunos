<?php

namespace Core;

use stdClass;

class RouteCollection
{
    private static array $routes = array();

    private function __construct()
    {
    }

    /**
     * @param string $method
     * @param string $pattern
     * @param array|string|callable $callback
     */
    public static function add(string $method, string $pattern, $callback): void
    {
        $pattern = implode('/', array_filter(explode('/', $pattern)));

        $pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';

        if (preg_match("/{[A-Za-z0-9_\-]+}/", $pattern)) {

            $pattern = preg_replace("/{[A-Za-z0-9_\-]+}/", "[A-Za-z0-9\_\-]{1,}", $pattern);
        }

        self::$routes[$method][$pattern] = $callback;
    }

    /**
     * @param string $method
     * @param string $path
     * @return false|stdClass
     */
    public static function find(string $method, string $path)
    {
        $path = implode('/', array_filter(explode('/', $path)));

        foreach (self::$routes[$method] as $pattern => $callback) {
            if (preg_match($pattern, $path, $params)) {
                $data = $params;
                $params = explode('/', array_shift($params));
                $obj = new stdClass();
                $obj->path = $path;
                $obj->params = self::values($data, $pattern);
                $obj->callback = $callback;
                return $obj;
            }
        }
        return false;
    }

    protected static function values($data, $pattern): array
    {
        $values = [];
        $val = explode('/', array_shift($data));
        if (preg_match("/[A-Za-z0-9]+/", $pattern, $matches)) {
            $pattern = explode('/', str_replace(["/^", "$/", "\\"], '', $pattern));
            $index = 0;
            foreach ($pattern as $piece) {
                if (preg_match("/^[\[][A-Za-z0-9]+/", $piece)) {

                    $values[] = $val[$index];
                }
                $index++;
            }
        }
        return $values;
    }
}
