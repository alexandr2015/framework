<?php

/**
 * Created by PhpStorm.
 * User: alex
 * Date: 14.03.16
 * Time: 15:16
 */

class Autoload
{
    private $class;

    private $path;

    public function __construct($className)
    {
        $this->class = $className;
    }

    protected function parseNameSpace()
    {
        $class = str_replace('-', '_', $this->class);
        $path = Config::get('root');
        $path .= '/'.str_replace('\\', '/', $class);
        $path .= '.php';

        $this->path = $path;
    }

    public function load()
    {
        $this->parseNameSpace();
        if (file_exists($this->path)) {
            require_once $this->path;
        } else {
            if (!$filePath = $this->loadFromProject()) {
                dd('file not found', $this);
            } else {
                dd($filePath);
            }
        }
    }

    protected function loadFromProject()
    {
        $root = Config::get('root');
        $this->class .= '.php';
        return $this->findFile($root);
    }

    protected function findFile($dir)
    {
        $filesInDir = scandir($dir);

        foreach ($filesInDir as $file) {
            if ($file[0] == '.') {
                continue;
            }
            $filePath = $dir . '/' . $file;
            var_dump($filePath);
            if ($file === $this->class) {
                return $filePath;
            } elseif (is_dir($filePath)) {
                return $this->findFile($filePath);
            } else {
                continue;
            }
        }

        return false;
    }
}