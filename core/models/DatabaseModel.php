<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 01.03.16
 * Time: 15:10
 */
namespace core\models;

class DatabaseModel extends BaseModel
{
    protected $visible = [];

    protected $hidden = [];

    protected $primaryKey = 'id';

    public function tableName()
    {
        $class = get_called_class();
        return strtolower(substr($class, strrpos($class, '\\') + 1, strlen($class)));
    }
}