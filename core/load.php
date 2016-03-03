<?php
require_once 'Router.php';
require_once 'config/Config.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once 'autoload.php';
Config::set('root', __DIR__ . '/..');
Config::set('app', __DIR__ . '/../application');
Config::set('config', __DIR__ . '/../config');
Config::set('core', __DIR__);
//Запуск роутинга

(new Router)->execute();