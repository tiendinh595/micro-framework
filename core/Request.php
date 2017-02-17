<?php
namespace core;

class Request
{

    private static  $_params = [],
                    $_instance = null,
                    $_method = null;

    /**
     * @return array
     */
    public static function getParams()
    {
        return self::$_params;
    }

    /**
     * @return null
     */
    public static function getMethod()
    {
        return self::$_method;
    }

    private function __construct()
    {
    }

    public static function init() {
        self::$_method = $_SERVER['REQUEST_METHOD'];
        self::$_params = $_REQUEST;

    }

    static function removeCharDange($plain) {
        if(is_array($plain)) {
            return;
        }

        return;
    }

    public static function getInstance()
    {
        if (self::$_instance == null)
            self::$_instance = new self();
        return self::$_instance;
    }

    public static function get($name)
    {
        return isset(self::$_params[$name]) ? self::$_params[$name] : null;
    }

}