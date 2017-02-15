<?php

function __autoload($file)
{
    $file = str_replace('\\', '/', $file);
    $path_file = ROOT . '/' . $file . '.php';
    if (file_exists($path_file))
        require_once $path_file;
    else
        die("Class {$file} not found");
}