<?php

/**
 * Created by PhpStorm.
 * User: alex
 * Date: 10.03.16
 * Time: 21:22
 */
namespace core\database\migration;

trait MigrationBuilder
{
    protected function createTable($tableName, $tableFields)
    {

    }

    protected function dropTable($tableName)
    {

    }

    /**
     * fields type
     */
    protected function integer()
    {
        return $this;
    }

    protected function string($length = 255)
    {
        return $this;
    }

    protected function default()
    {
        return $this;
    }

    protected function notNull()
    {
        return $this;
    }

    protected function text()
    {
        return $this;
    }

    protected function date()
    {
        return $this;
    }
}