<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 11.03.16
 * Time: 11:07
 */
namespace core\console;

class Console
{
    protected $command;

    public function __construct($command)
    {
        $this->command = $command;
    }

    public function run()
    {
        print_r($this);
    }

    public function loadConfig()
    {

    }
}