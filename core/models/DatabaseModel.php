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

    public function __construct(array $data = [])
    {
        if (isset($data)) {
            var_dump($data);
        }
    }

    /**
     * @var $_connection \PDO object
     */
    private $_connection;

    private $_private;

    protected $visible = [];

    protected $hidden = [];

    protected $primaryKey = 'id';

    protected function setConnection()
    {
        $this->_connection = Connection::getConnection();
    }

    protected function addFields(array $data)
    {
        foreach ($data as $key => $value) {
            $this->_private[$key] = $value;
        }

        return $this;
    }

    public function all()
    {
        if (!$this->_connection) {
            $this->setConnection();
        }
        $this->buildSql();
        $this->setRawSql();
        $result = $this->_connection->prepare($this->rawSql);
        $result->execute();
        $response = $result->fetchAll(\PDO::FETCH_ASSOC);
        if ($this->_asArray) {
            return $response;
        } else {
            $objects = [];
            foreach ($response as $obj) {
                $objects[] =  $this->addFields($obj);
            }

            return $objects;
        }
    }
}