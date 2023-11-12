<?php

/**
 * Simple Router - to load the page based on route
 *
 * @package  Kapi
 * @version  1.0.0
 * @author   K V P <kurianvarkey@yahoo.com>
 * @link     http://www.net4ideas.com
 */

namespace Kapi;

final class Router
{
    /**
     * routes array
     *
     * @var array
     */
    private static array $routes = [];

    /**
     * Fallback functions
     *
     * @var mixed
     */
    private static $fn_fallback = null;

    /**
     * Setting the Get route
     *
     * @param string $route
     * @param mixed $callback
     * @return void
     */
    public static function get(string $route, mixed $callback): void
    {
        if (self::getRequestMethod() == 'GET') {
            self::$routes = array_merge(self::$routes, [$route => $callback]);
        }
    }

    /**
     * Fallback function
     *
     * @param function $callback
     * @return void
     */
    public static function fallback($callback): void
    {
        self::$fn_fallback = $callback;
    }

    /**
     * Applying the routes
     *
     * @return void
     */
    public static function apply(): void
    {
        $request_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        $request_url = rtrim($request_url, '/');
        $request_url = trim(strtok($request_url, '?'));
        $request_url_parts = explode('/', $request_url);
        array_shift($request_url_parts);

        if ($request_url == '') {
            $request_url = '/';
        }

        $result = false;
        foreach (self::$routes as $route => $callback) {
            $route_parts = explode('/', $route);
            array_shift($route_parts);

            if (($request_url == $route) || ($route_parts[0] == '' && count($request_url_parts) == 0)) {
                if (is_callable($callback)) {
                    call_user_func_array($callback, []);
                }
                $result = true;
                break;
            } else {
                if (
                    $result = self::route(
                        route_parts: $route_parts,
                        request_url_parts: $request_url_parts,
                        callback: $callback
                    )
                ) {
                    break;
                }
            }
        }

        if (!$result) {
            if (!empty(self::$fn_fallback)) {
                call_user_func_array(self::$fn_fallback, []);
            }
        }
    }

    /**
     * Getting the request method
     *
     * @return string
     */
    private static function getRequestMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? '';
    }

    /**
     * Processing the routes
     *
     * @param array $route_parts
     * @param array $request_url_parts
     * @param mixed $callback
     * @return bool
     */
    private static function route(array $route_parts, array $request_url_parts, mixed $callback): bool
    {
        if (count($route_parts) != count($request_url_parts)) {
            return false;
        }

        //getting the parameters
        $parameters = [];
        for ($i = 0; $i < count($route_parts); $i++) {
            $route_part = $route_parts[$i];
            if (preg_match("/^[$]/", $route_part)) {
                $route_part = ltrim($route_part, '$');
                array_push($parameters, $request_url_parts[$i]);
                $$route_part = $request_url_parts[$i];
            } else if ($route_parts[$i] != $request_url_parts[$i]) {
                return false;
            }
        }

        // Callback function
        if (is_callable($callback)) {
            call_user_func_array($callback, $parameters);
        }

        return true;
    }
}
