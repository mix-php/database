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
     * @var string
     */
    protected $_table = '';

    /**
     * @var string
     */
    protected $_select = '';

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
     * 设置表
     * @param string $table
     * @return $this
     */
    public function table(string $table)
    {
        $this->_table = $table;
        return $this;
    }

    

}
