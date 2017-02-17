<?php
require_once './config/define.php';
include './core/AutoLoad.php';

$router = new core\Router();
require_once './app/routes.php';

\core\Request::init();
\core\View::setDirView(DIR_VIEW);

try {
    $router->dispath();
} catch (Exception $e) {
    echo $e->getMessage();
}
