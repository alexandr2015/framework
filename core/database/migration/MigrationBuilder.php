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
    private $_queries = [];

    protected function createTable($tableName, $tableFields)
    {
        $sql = 'create table ' . $tableName . '(';
        foreach ($tableFields as $field => $fieldType) {
            $sql .= '`' . $field . '` ' . $fieldType . ',';
        }
        $sql = rtrim($sql, ','); // delete last ,
        $sql .= ')';
        $this->addCurrentQueryToAll($sql);
    }

    protected function dropTable($tableName)
    {
        $sql = 'DROP TABLE ' . $tableName;
        $this->addCurrentQueryToAll($sql);
    }

    public function getAllQueries()
    {
        return $this->_queries;
    }
    /**
     * fields type
     */
    protected function primaryKey()
    {

        return $this;
    }

    protected function integer()
    {
        return $this;
    }

    protected function string($length = 255)
    {
        return $this;
    }

    protected function defaultValue()
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

    public function addCurrentQueryToAll($query)
    {
        $this->_queries[] = $query;
    }
}