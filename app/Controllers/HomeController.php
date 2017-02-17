<?php

/**
 * Created by PhpStorm.
 * User: TienDinh
 * Date: 2/9/2017
 * Time: 4:46 PM
 */

namespace app\Controllers;

use core\Request;
use core\View;

class HomeController
{
    function index(Request $request)
    {
        echo '<pre>';
        print_r($request);
        echo 'index method';
    }

    function view($slug)
    {
        View::render('view', $slug);
    }
}