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
    private $_distinct = false;

    private $_select = '*';

    private $_where;

    private $_action;

    private $_limit;

    private $_orderBy;

    private $_having;

    private $_join;

    private $_asArray;

    public $sql;

    public $rawSql;

    private $_params = [];

    public function tableName()
    {
        $class = get_called_class();
        return strtolower(substr($class, strrpos($class, '\\') + 1, strlen($class)));
    }

    /**
     * setters
     */
    public function setJoin($join)
    {
        $this->_join = $join;
    }

    public function setDistinct($distinct)
    {
        $this->_distinct = $distinct;
    }

    public function setAsArray($asArray = true)
    {
        $this->_asArray = $asArray;
    }

    public function setLimit($limit)
    {
        $this->_limit = 'limit' . $limit;
    }

    public function setOrderBy($orderBy)
    {
        $this->_orderBy = $orderBy;
    }

    public function setRawSql()
    {
        $this->rawSql = str_replace(array_keys($this->_params), array_values($this->_params), $this->sql);
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
        if (is_null($num)) {
            $alias = ':'.$alias;
        }
        if (array_key_exists($alias.$num, $this->_params)) {
            $num = (is_null($num)) ? 1 : $num + 1;

            return $this->setParams($alias, $value, $num);
        } else {
            $alias .= $num;
            $this->_params[$alias] = $value;

            return $alias;
        }
    }
    /**
     * build queries
     */
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

    public function whereBetween($field, $from, $to)
    {
        $parameters = ['between', $field, $from, $to, 'and'];
        call_user_func_array(
            [$this, 'parseWhere'],
            $parameters
        );

        return $this;
    }

    public function orWhereBetween($field, $from, $to)
    {
        $parameters = ['between', $field, $from, $to, 'or'];
        call_user_func_array(
            [$this, 'parseWhere'],
            $parameters
        );

        return $this;
    }

    public function whereIn($field, $values)
    {
        $array[$field] = $values;
        $this->parseWhere($array, 'and');

        return $this;
    }

    public function orWhereIn($field, $values)
    {
        $array[$field] = $values;
        $this->parseWhere($array, 'or');

        return $this;
    }

    public function distinct()
    {
        $this->setDistinct(true);
    }

    public function orderBy($orberBy)
    {
        $this->setOrderBy($orberBy);
        return $this;
    }

    public function asArray()
    {
        $this->setAsArray();
        return $this;
    }

    public function one()
    {
        $this->setLimit(1);
    }

    public function buildSql()
    {
        $sql = 'SELECT ' . $this->_select . ' FROM ' . $this->tableName();
        if ($this->_where) {
            $sql .= ' WHERE';
            foreach($this->_where as $where) {
                $sql .= ' ' . $where;
            }
        }

        if ($this->_limit) {
            $sql .= ' LIMIT ' . $this->_limit;
        }

        if ($this->_orderBy) {
            $sql .= ' ORDER BY ' . $this->_orderBy;
        }

        $this->sql = $sql;
    }

    public function parseWhere()
    {
        $count = func_num_args();
        $where = null;
        $loginOperator = null;
        switch ($count) {
            case 2: {
                $condition = func_get_arg(0);
                if (is_array($condition)) {
                    $field = key($condition);
                    $value = $condition[$field];
                    if (is_array($value)) {
                        $in = implode(',', array_map(function($item) use ($field) {
                            return '\'' . $this->setParams($field, $item) . '\'';
                        }, $value));
                        $where = $field . ' in (' . $in . ')';
                    } else {
                        $where = $field . ' = ' . $this->setParams($field, $value);
                    }
                } else {
                    $where = $condition;
                }
                $loginOperator = func_get_arg(1);
                break;
            }
            case 3: {
                $field = func_get_arg(0);
                $value = func_get_arg(1);
                $loginOperator = func_get_arg(2);
                $alias = $this->setParams($field, $value);
                $where = $field . ' = ' . $alias;
                break;
            }
            case 4: {
                $field = func_get_arg(0);
                $operator = func_get_arg(1);
                $value = func_get_arg(2);
                $loginOperator = func_get_arg(3);
                $alias = $this->setParams($field, $value);
                $where = $field . ' ' . $operator . ' ' . $alias;
                break;
            }
            case 5: {
                $operator = func_get_arg(0);
                if (strtolower($operator) == 'between') {
                    $field = func_get_arg(1);
                    $fromValue = func_get_arg(2);
                    $toValue = func_get_arg(3);
                    $loginOperator = func_get_arg(4);
                    $aliasFrom = $this->setParams($field, $fromValue);
                    $aliasTo = $this->setParams($field, $toValue);
                    $where = $field . ' BETWEEN ' .$aliasFrom . ' AND ' . $aliasTo;
                }
                break;
            }
            default : {
                dd('error where count', $this);
            }
        }
        $this->setWhere($where, $loginOperator);

        return $this;
    }
}