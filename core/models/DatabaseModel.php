<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 01.03.16
 * Time: 15:10
 */
namespace core\models;
use core\Connection;
use core\models\model_traits\QueryBuilder;

class DatabaseModel extends BaseModel
{
    use QueryBuilder;
    private $_connection;

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
}