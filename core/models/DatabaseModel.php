<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 01.03.16
 * Time: 15:10
 */
namespace core\models;
use core\Connection;

class DatabaseModel extends BaseModel
{
    private $_connection;

    private $_select;
    private $_where;

    protected $visible = [];

    protected $hidden = [];

    protected $primaryKey = 'id';

    public function __construct()
    {
        $this->setConnection();
    }

    public function tableName()
    {
        $class = get_called_class();
        return strtolower(substr($class, strrpos($class, '\\') + 1, strlen($class)));
    }

    protected function setConnection()
    {
        $this->_connection = Connection::getConnection();
    }

    public function select(array $select)
    {
        $this->_select = $select;
        return $this;
    }

    public function where(array $where)
    {
        $this->_where = $where;
        return $this;
    }
}