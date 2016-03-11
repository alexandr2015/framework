<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 11.03.16
 * Time: 11:07
 */
class Console
{
    protected $command;

    protected $params;

    private $color;

    public function __construct($command, $params)
    {
        $this->command = $command;
        $this->params = $params;
        $this->color = new Colors();
        $this->parseCommand();
    }

    public function parseCommand()
    {
        list($class, $action) = explode(':', $this->command);
        $class = ucfirst($class);
        $action = 'action' . ucfirst($action);
        $classPath = Config::get('core') . '/console/classes/' . $class . '.php';
        if (!file_exists($classPath)) {
            dd('class '.$class.' not found');
        }
        include $classPath;
        $classObject = new $class();
        if (!method_exists($classObject, $action)) {
            dd('class '.$class.' not found method');
        }
        $response = call_user_func_array([
            $classObject,
            $action
        ], $this->params);
        $this->printText($response);
    }


    public function run()
    {
    }

    public function loadConfig()
    {

    }

    public function printText($string)
    {
        echo $this->color->getColoredString($string, 'green', 'black') . "\n";
    }

}