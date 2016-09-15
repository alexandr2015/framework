<?php

namespace core\controllers;

class Controller
{
    public $model;
    public $view;

    function __construct()
    {
    }

    public function view($file, $params = [])
    {
        $pos = strpos(get_called_class(), 'Controller');
        $className = strtolower(substr(get_called_class(), 0, $pos));
        foreach ($params as $variableName => $variableValue) {
            ${$variableName} = $variableValue;
        }

        return include \Config::get('views')  . "/{$className}/" . $file . '.php';
    }

}