<?php

namespace Mix\Database;

use Mix\Database\Query\BuildHelper;
use Mix\Database\Query\Expression;
use Mix\Pool\ConnectionPoolInterface;

/**
 * Class QueryBuilder
 * @package Mix\Database
 * @author liu,jian <coder.keda@gmail.com>
 */
class QueryBuilder
{

    /**
     * 连接
     * @var PDOConnectionInterface
     */
    public $connection;

    /**
     * @var string
     */
    protected $_table = '';

    /**
     * @var array
     */
    protected $_select = [];

    /**
     * @var array
     */
    protected $_join = [];

    /**
     * @var array
     */
    protected $_where = [];

    /**
     * @var array
     */
    protected $_orderBy = [];

    /**
     * @var array
     */
    protected $_groupBy = [];

    /**
     * @var array
     */
    protected $_having = [];

    /**
     * @var int
     */
    protected $_offset = 0;

    /**
     * @var int
     */
    protected $_limit = 0;

    /**
     * 使用静态方法创建实例
     * @param PDOConnectionInterface $db
     * @return QueryBuilder
     */
    public static function new(PDOConnectionInterface $connection)
    {
        return new static($connection);
    }

    /**
     * QueryBuilder constructor.
     * @param PDOConnectionInterface $db
     */
    public function __construct(PDOConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * table
     * @param string $table
     * @return $this
     */
    public function table(string $table)
    {
        $this->_table = $table;
        return $this;
    }

    /**
     * select
     * @param mixed ...$fields
     * @return $this
     */
    public function select(...$fields)
    {
        $this->_select = array_merge($this->_select, $fields);
        return $this;
    }

    /**
     * join
     * @param string $table
     * @param array $on
     */
    public function join(string $table, array $on)
    {
        array_push($this->_join, ['INNER JOIN', $table, $on]);
        return $this;
    }

    /**
     * leftJoin
     * @param string $table
     * @param array $on
     * @return $this
     */
    public function leftJoin(string $table, array $on)
    {
        array_push($this->_join, ['LEFT JOIN', $table, $on]);
        return $this;
    }

    /**
     * rightJoin
     * @param string $table
     * @param array $on
     * @return $this
     */
    public function rightJoin(string $table, array $on)
    {
        array_push($this->_join, ['RIGHT JOIN', $table, $on]);
        return $this;
    }

    /**
     * fullJoin
     * @param string $table
     * @param array $on
     * @return $this
     */
    public function fullJoin(string $table, array $on)
    {
        array_push($this->_join, ['FULL JOIN', $table, $on]);
        return $this;
    }

    /**
     * where
     * @param array $where
     * @return $this
     */
    public function where(array $where)
    {
        array_push($this->_where, $where);
        return $this;
    }

    /**
     * orderBy
     * @param string $field
     * @param string $order
     * @return $this
     */
    public function orderBy(string $field, string $order)
    {
        if (!in_array($order, ['asc', 'desc'])) {
            throw new \RuntimeException('Sort can only be asc or desc.');
        }
        array_push($this->_orderBy, [$field, strtoupper($order)]);
        return $this;
    }

    /**
     * groupBy
     * @param mixed ...$fields
     * @return $this
     */
    public function groupBy(...$fields)
    {
        $this->_groupBy = array_merge($this->_groupBy, $fields);
        return $this;
    }

    /**
     * having
     * @param $field
     * @param $operator
     * @param $condition
     * @return $this
     */
    public function having($field, $operator, $condition)
    {
        array_push($this->_having, [$field, $operator, $condition]);
        return $this;
    }

    /**
     * offset
     * @param int $length
     * @return $this
     */
    public function offset(int $length)
    {
        $this->_offset = $length;
        return $this;
    }

    /**
     * limit
     * @param int $length
     * @return $this
     */
    public function limit(int $length)
    {
        $this->_limit = $length;
        return $this;
    }

    public function get()
    {
        $sql = [];
        switch (true) {
            case $this->_select:
                $select = $this->_select;
                // 原始方法
                foreach ($select as $key => $item) {
                    if ($item instanceof Expression) {
                        $select[$key] = $item->getValue();
                    }
                }
                $select = implode(', ', $select);
                $sql[]  = ["SELECT {$select}"];

            case $this->_join:
                foreach ($this->_join as $item) {
                    list($type, $table, $on) = $item;
                    $condition = BuildHelper::buildJoinOn($on);
                    $sql[]     = ["{$type} {$table} ON {$condition}"];
                }

            case $this->_where:
                list($subSql, $subParams) = BuildHelper::buildWhere($this->_where);
                $sql[] = ["WHERE {$subSql}", 'params' => $subParams];

            case $this->_orderBy:
                $subSql = [];
                foreach ($this->_orderBy as $item) {
                    list($field, $order) = $item;
                    $subSql[] = "{$field} {$order}";
                }
                $sql[] = ["ORDER BY " . implode(', ', $subSql)];
        }

        var_dump($sql);

        //return $this->connection->createCommand($sql)->queryAll();
    }

    public function first()
    {

    }

}
