<?php
require_once 'Router.php';
require_once 'config/Config.php';
require_once __DIR__ . '/../helpers/functions.php';
Config::set('app', __DIR__ . '/../application');
Config::set('config', __DIR__ . '/../config');
Config::set('core', __DIR__);
//require_once 'core/model.php';
//require_once 'core/view.php';
//require_once 'core/Controller.php';
//Запуск роутинга

(new Router)->execute();