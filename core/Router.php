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
    const DEFAULT_ACTION = 'index';

    private $_config = [];

    private $_module = false;

    private $_controller = null;

    private $_action = self::DEFAULT_ACTION;

    private $_params = [];

    private $_controllerPath = null;

    public function __construct()
    {
        $this->loadConfig();
        $this->parseFullUrl();
    }

    /**
     * setters
     */
    protected function setModule($module, $withDefaultController = false)
    {
        $this->_module = $module;
        if ($withDefaultController) {
            if (!array_key_exists('defaultController', $this->_config['modules'][$module])) {
                dd('default controller in module not found', $this);
            }
            if (array_key_exists('defaultAction', $this->_config['modules'][$module])) {
                $this->setAction($this->_config['modules'][$module]['defaultAction']);
            }
            $this->setController($this->_config['modules'][$module]['defaultController']);
        }
    }
    protected function setController($controller)
    {
        $this->_controller = $controller;
    }

    protected function setAction($action)
    {
        $this->_action = 'action' . ucfirst($action);
    }

    protected function setDefaultAction()
    {
        if ($this->_config['defaultAction']) {
            $this->setAction($this->_config['defaultAction']);
        }
    }

    protected function setDefaultController()
    {
        if ($this->_config['defaultController']) {
            $this->setControllerInfo($this->_config['defaultController']);
        }
    }

    protected function setControllerPath($hasModule = false)
    {
        $modulePath = ($hasModule) ? '/' . $this->_module : '';
        $this->_controllerPath = Config::get('app') . $modulePath . '/controllers/' . ucfirst($this->_controller) . 'Controller.php';
    }

    protected function setControllerInfo($controller, $hasModule = false)
    {
        $this->setController($controller);
        $this->setControllerPath($hasModule);
    }

    protected function loadConfig()
    {
        $routerConfigFile = Config::get('config') . '/router.php';
        if (!file_exists($routerConfigFile)) {
            dd('error config file', $this); die;
        }
        $this->_config = include $routerConfigFile;
    }

    protected function parseFullUrl()
    {
        $this->parseUrl();
        $this->parseParams();
    }

    protected function parseRequestIri()
    {
        $url = ltrim($_SERVER['REQUEST_URI'], '/');
        $url = (stristr($url, '?')) ? substr($url, 1, strpos($url, '?') - 1) : substr($url, 1, strlen($url));
        if ($url === false) {
            return [];
        }

        return explode('/', $url);
    }

    protected function parseUrl()
    {
        $url = $this->parseRequestIri();
        $count = sizeof($url);

        switch($count) {
            case 0: {
                $this->setDefaultController();
                $this->setDefaultAction();
                break;
            }
            case 1: {
                $modules = array_keys($this->_config['modules']);
                if (in_array($url[0], $modules)) {
                    $this->setModule($url[0], true);
                } else {
                    $this->setControllerInfo($url[0]);
                    $this->setDefaultAction();
                }
                break;
            }
            case 2: {
                $modules = array_keys($this->_config['modules']);
                if (in_array($url[0], $modules)) {
                    $this->setModule($url[0], true);
                    $this->setControllerInfo($url[1], true);
                } else {
                    $this->setController($url[0]);
                    $this->setControllerInfo($url[0]);
                    $this->setAction($url[1]);
                }
                break;
            }
            case 3: {
                $this->setModule($url[0]);
                $this->setControllerInfo($url[1], true);
                $this->setAction($url[2]);
                break;
            }
        }
    }

    protected function parseParams()
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
        } else {
            dd('Controller not found', $this, $this->_controllerPath);
        }

        $controller = $this->getControllerObject();

        if (method_exists($controller, $this->_action)) {
            //@todo add try catch
            call_user_func_array([
                $controller,
                $this->_action
            ], $this->_params);
        } else {
            dd('Method not found', $this);
            //routing error
        }
    }

    protected function getControllerObject()
    {
        $controller = ucfirst($this->_controller) . 'Controller';
        return new $controller;
    }
}