<?php

/**
 * Created by PhpStorm.
 * User: alex
 * Date: 03.03.16
 * Time: 10:56
 */

spl_autoload_register(function($class) {
    $path = parseNameSpace($class);
    if (file_exists($path)) {
        require_once $path;
    } else {
        dd($class . ' not found');
    }
});

function parseNameSpace($class)
{
    $class = str_replace('-', '_', $class);
    $path = Config::get('root');
    $path .= '/'.str_replace('\\', '/', $class);
    $path .= '.php';

    return $path;
}
