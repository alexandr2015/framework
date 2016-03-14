<?php

/**
 * Created by PhpStorm.
 * User: alex
 * Date: 03.03.16
 * Time: 10:56
 */
include 'Autoload.php';

spl_autoload_register(function($class) {
    (new Autoload($class))->load();
});
