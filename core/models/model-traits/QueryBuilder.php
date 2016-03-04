<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 04.03.16
 * Time: 10:46
 */
namespace core\models\model_traits;

trait QueryBuilder
{
    private $_select;

    private $_where;

    private $_action;

    private $_limit;

    private $_orderBy;

    private $_having;

    private $_params = [];

    public function select()
    {
        $value = func_get_arg(0);
        if (is_array($value)) {
            $value = implode(', ', $value);
        }
        $this->setSelect($value);

        return $this;
    }

    public function where()
    {
        $parameters = array_merge(func_get_args(), ['and']);
        call_user_func_array(
            [$this, 'parseWhere'],
            $parameters
        );

        return $this;
    }

    public function andWhere()
    {
        $parameters = array_merge(func_get_args(), ['and']);
        call_user_func_array(
            [$this, 'parseWhere'],
            $parameters
        );

        return $this;
    }

    public function orWhere()
    {
        $parameters = array_merge(func_get_args(), ['or']);
        call_user_func_array(
            [$this, 'parseWhere'],
            $parameters
        );

        return $this;
    }

    public function parseWhere()
    {
        $count = func_num_args();
        $where = null;
        $operator = 'and';
        switch ($count) {
            case 3: {
                $field = func_get_arg(0);
                $value = func_get_arg(1);
                $operator = func_get_arg(2);
                $alias = $this->setParams(':'.$field, $value);
                $where = $field . ' = ' . $alias;
                break;
            }
            case 4: {
                $field = func_get_arg(0);
                $op = func_get_arg(1);
                $value = func_get_arg(2);
                $operator = func_get_arg(3);
                $alias = $this->setParams(':'.$field, $value);
                $where = $field . ' ' . $op . ' ' . $alias;
                break;
            }
            default : {
                dd('error where count', $this);
            }
        }
        $this->setWhere($where, $operator);

        return $this;
    }

    protected function setSelect($select)
    {
        $this->_select = $select;
    }

    protected function setWhere($where, $op = 'and')
    {
        if (empty($this->_where)) {
            $this->_where[] = $where;
        } else {
            $this->_where[] = $op . ' ' . $where;
        }
    }

    protected function setParams($alias, $value, $num = null)
    {
        if (array_key_exists($alias.$num, $this->_params)) {
            $num = (is_null($num)) ? 1 : $num + 1;

            return $this->setParams($alias, $value, $num);
        } else {
            $alias .= $num;
            $this->_params[$alias] = $value;


            return $alias;
        }
    }
}