<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 26.02.16
 * Time: 13:32
 */
include 'config/Config.php';

class Router
{
    private $_config = [];
    private $_module = false;
    private $_controller = null;
    private $_action = null;
    private $_params = [];
    private $_controllerPath = null;
    private $_modelPath = null;

    public function __construct()
    {
        $this->loadConfig();
        $this->parseUrl();
        $this->parseParams();
    }

    public function loadConfig()
    {
        $routerConfigFile = Config::get('config') . '/router.php';
        if (!file_exists($routerConfigFile)) {
            var_dump('error config file'); die;
        }
        $this->_config = include $routerConfigFile;
    }

    public function parseUrl()
    {
        //@todo refactor ucfirst($this->_controller);
        $url = $_SERVER['REQUEST_URI'];
        $url = (stristr($url, '?')) ? substr($url, 1, strpos($url, '?') - 1) : $url;
        $url = explode('/', $url);
        if (isset($url[2])) { // module exist in url
            $this->_module = $url[0];
            $this->_controller = $url[1];
            $this->_controllerPath = Config::get('app') . '/' . $this->_module . '/controllers/' . ucfirst($this->_controller) . 'Controller.php';
            $this->_modelPath = Config::get('app') . '/' . $this->_module . '/models/' . ucfirst($this->_controller) . '.php';
            $this->_action = $url[2];
        } else {
            $this->_controller = $url[0];
            $this->_action = $url[1];
            $this->_controllerPath = Config::get('app') . '/controllers/' . ucfirst($this->_controller) . 'Controller.php';
            $this->_modelPath = Config::get('app') . '/models/' . ucfirst($this->_controller) . '.php';
        }
    }

    public function parseParams()
    {
        if (!empty($_SERVER['QUERY_STRING'])) {
            $params = explode('&', $_SERVER['QUERY_STRING']);
            foreach ($params as $param) {
                list ($name, $value) = explode('=', $param);
                $this->_params[$name] = $value;
            }
        }
    }

    public function execute()
    {
        if (file_exists($this->_controllerPath)) {
            include $this->_controllerPath;
            dd($this->_controllerPath);
            new DefaultController();
            die;
        } else {
            var_dump('Controller not found'); die;
        }
        if (file_exists($this->_modelPath)) {
            include $this->_modelPath;
        } else {
            var_dump('Model not found'); die;
        }
//        dd($this);
        $controller = $this->getControllerObject();
        if (method_exists($controller, $this->_action)) {
            call_user_func([
                $controller,
                $this->_action
            ], $this->_params);
        } else {
            var_dump('Method not found'); die;
            //routing error
        }
    }

    public function getControllerObject()
    {
        $controller = ucfirst($this->_controller) . 'Controller';
        return new $controller;
    }
}