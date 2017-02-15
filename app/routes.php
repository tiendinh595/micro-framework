<?php
/**
 * Created by PhpStorm.
 * User: TienDinh
 * Date: 2/9/2017
 * Time: 4:47 PM
 */

$router->get('/', function () {
   View::render('home');
})->middleware('admin');

$router->get(['url'=>'/abc', 'middleware'=>'admin'], function () {
    echo 'admin page';
});

$router->get('/news/{id}', function($id) {
    echo 'news '. $id;
});
$router->get('/post/{slug}', 'HomeController@view');

$router->group('/admin', function () use ($router) {
    $router->get('/', function () {
        \core\View::render('admin');
    });
    $router->get('/add', function () {
        echo 'add page';
    });
})->middleware('admin');

$router->get('/test', function() {
	$a = 'vu ';
	$b = 'tien dinh';

	echo $a,'|',$b;
});