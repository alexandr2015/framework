<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 26.02.16
 * Time: 15:15
 */
if (!function_exists('dd')) {
    function dd()
    {
        foreach(func_get_args() as $var) {
            var_dump($var);
        }
        die;
    }
}