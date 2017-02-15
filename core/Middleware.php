<?php
/**
 * Created by PhpStorm.
 * User: TienDinh
 * Date: 2/10/2017
 * Time: 8:59 AM
 */

namespace core;


class Middleware
{
    public static function executeMiddleware($middleware, \Closure $next)
    {
        $middlewareClassName = self::getMiddlewares()[$middleware];
        return (new $middlewareClassName)->handle(function () use ($next) {
            $next();
        });
    }

    private static function getMiddlewares()
    {
        return require DIR_APP . '/middleware.php';
    }

}