<?php

/**
 * Created by PhpStorm.
 * User: TienDinh
 * Date: 2/9/2017
 * Time: 4:46 PM
 */

namespace app\Controllers;

use core\View;

class HomeController
{
    function index()
    {
        echo 'index method';
    }

    function view($slug)
    {
        View::render('view', $slug);
    }
}