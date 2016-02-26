<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 26.02.16
 * Time: 14:23
 */

class Config
{
    private static $_config = [];

    private function __construct() {}

    public static function set($name, $value)
    {
        if (self::checkKey($name)) {
            //throw error
        }
        self::$_config[$name] = $value;
    }

    public static function get($name, $defaultValue = null)
    {
        if (self::checkKey($name)) {
            return self::$_config[$name];
        }

        return $defaultValue;
    }

    public static function checkKey($name)
    {
        return array_key_exists($name, self::$_config);
    }

    private function __clone() {}
}