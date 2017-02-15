<?php

/**
 * Created by PhpStorm.
 * User: TienDinh
 * Date: 2/10/2017
 * Time: 9:02 AM
 */

namespace app\Middleware;

class AdminMiddleware
{
    function handle(\Closure $next, $agrs = []) {
        $a = 1;
        if($a!=1)
            echo 'not';
        else
            return $next();
    }
}