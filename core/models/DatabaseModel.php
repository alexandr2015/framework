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
    /**
     * @var $_connection \PDO object
     */
    private $_connection;

    protected $visible = [];

    protected $hidden = [];

    protected $primaryKey = 'id';

    public function __construct()
    {
        $this->setConnection();
    }

    protected function setConnection()
    {
        $this->_connection = Connection::getConnection();
    }

    public function all()
    {
        $this->buildSql();
        $this->setRawSql();
        $result = $this->_connection->prepare($this->sql)->execute($this->_params);
        dd($result, $this);
        return 1;
    }
}