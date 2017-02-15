<?php
/**
 * Created by PhpStorm.
 * User: TienDinh
 * Date: 2/10/2017
 * Time: 8:57 AM
 */

namespace core;


class View
{
    private static $dir_view;

    /**
     * @param mixed $dir_view
     */
    public static function setDirView($dir_view)
    {
        self::$dir_view = $dir_view;
    }

    /**
     * @return mixed
     */
    public static function getDirView()
    {
        return self::$dir_view;
    }

    /**
     * @param string $file_view
     * @param array $args
     * @throws \Exception
     */
    public static function render($file_view, $args = [])
    {
        if(!is_array($args))
            throw new \Exception("param must type array");
        extract($args);
        $path_view = self::$dir_view.'/'.trim($file_view, '/').'.php';
        if(!file_exists($path_view))
            throw new \Exception("view {$file_view} not exists");
        include $path_view;
    }
}