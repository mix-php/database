<?php

namespace Mix\Database;

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
     * @var array
     */
    public $table = '';

    /**
     * @var string
     */
    public $select = '';

    /**
     * @var array
     */
    public $join = [];

    /**
     * @var array
     */
    public $where = [];

    /**
     * @var array
     */
    public $orderBy = [];

    /**
     * @var array
     */
    public $groupBy = [];

    /**
     * @var array
     */
    public $having = [];

    /**
     * @var int
     */
    public $offset = 0;

    /**
     * @var int
     */
    public $limit = 0;

    /**
     * 使用静态方法创建实例
     * @param $db
     * @return $this
     */
    public static function new($db)
    {
        return new static($db);
    }

    /**
     * QueryBuilder constructor.
     * @param $db
     */
    public function __construct($db)
    {
        switch (true) {
            case $db instanceof ConnectionPoolInterface:
                $this->connection = $db->getConnection();
                break;
            case $db instanceof PDOConnectionInterface:
                $this->connection = $db;
                break;
            default:
                throw new \RuntimeException('$db type is not \'Mix\Pool\ConnectionPoolInterface\' or \'Mix\Database\PDOConnectionInterface\'');
        }
    }

    /**
     * 释放连接
     * @return bool
     */
    public function release()
    {
        if (!method_exists($this->connection, 'release')) {
            return false;
        }
        $this->connection->release();
        return true;
    }


    public function table($table)
    {

    }

}
