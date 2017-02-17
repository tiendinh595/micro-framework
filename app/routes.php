<?php
/**
 * Created by PhpStorm.
 * User: TienDinh
 * Date: 2/9/2017
 * Time: 4:47 PM
 */

$router->get('/', function () {
        try {
        $dns = 'mysql:host=localhost;dbname=caplayeuthuong;charset=utf8';
        $username = 'root';
        $password = '';
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        $pdo = new PDO($dns, $username, $password, $opt);

        $stmt = $pdo->prepare('select id, name from tbl_posts where id = :id');
//        $stmt->bindValue('id', '24', PDO::PARAM_INT);
        $id = 24;
        $stmt->bindParam('id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetchAll();
        echo '<pre>';
        print_r($data);
        echo '</pre>';

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
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

$router->get('/index', 'HomeController@index');