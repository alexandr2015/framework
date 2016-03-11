#!/usr/bin/php
<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 11.03.16
 * Time: 11:08
 */
ini_set('display_errors', 1);
require_once 'core/config/Config.php';
require_once __DIR__ . '/helpers/functions.php';
require_once 'core/autoload.php';
require_once 'core/console/Console.php';
Config::set('root', __DIR__ . '/..');
Config::set('app', __DIR__ . '/../application');
Config::set('config', __DIR__ . '/../config');
Config::set('core', __DIR__);

$command = $argv[1];
$console = new Console($command);
$console->loadConfig();
$console->run();