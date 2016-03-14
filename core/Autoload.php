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
                dd('file not found', $this, $filePath);
            } else {
                if (file_exists($filePath)) {
                    require_once $filePath;
                } else {
                    dd('not found', $this);
                }
            }
        }
    }

    protected function loadFromProject()
    {
        $root = Config::get('core');
        $this->class .= '.php';
        $file = false;
        $this->findFile($root, $file);
        return $file;
    }

    protected function findFile($dir, &$exists)
    {
        $filesInDir = scandir($dir);
        foreach ($filesInDir as $file) {
            if ($file[0] == '.') {
                continue;
            }
            $filePath = $dir . '/' . $file;
            if ($file == $this->class) {
                $exists = $filePath;
                return;
            } elseif (is_dir($filePath)) {
                $this->findFile($filePath, $exists);
                if ($exists !== false) {
                    return;
                }
            }
        }
    }
}