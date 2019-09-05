<?php

namespace Mix\Database\MasterSlave;

use Mix\Database\Base\AbstractConnection;

/**
 * Class Connection
 * @package Mix\Database\MasterSlave
 * @author liu,jian <coder.keda@gmail.com>
 */
class Connection extends AbstractConnection
{

    /**
     * 主服务器组
     * @var array
     */
    public $masters = [];

    /**
     * 配置主服务器
     * @var array
     */
    public $masterConfig = [];

    /**
     * 从服务器组
     * @var array
     */
    public $slaves = [];

    /**
     * 配置从服务器
     * @var array
     */
    public $slaveConfig = [];

    /**
     * PDO Master
     * @var \PDO
     */
    protected $_pdoMaster;

    /**
     * PDO Slave
     * @var \PDO
     */
    protected $_pdoSlave;

    /**
     * 使用主库
     * @var bool|null
     */
    protected $_useMaster;

    /**
     * 关闭连接
     * @return bool
     */
    public function disconnect()
    {
        parent::disconnect();
        $this->_pdoMaster = null;
        $this->_pdoSlave  = null;
        $this->_useMaster = null;
        return true;
    }

    /**
     * 使用主库
     * @return $this
     */
    public function useMaster()
    {
        $this->_useMaster = true;
        return $this;
    }

    /**
     * 返回结果集
     * @return \PDOStatement
     */
    public function query()
    {
        return $this->call(__FUNCTION__);
    }

    /**
     * 返回一行
     * @return mixed
     */
    public function queryOne()
    {
        return $this->call(__FUNCTION__);
    }

    /**
     * 返回多行
     * @return array
     */
    public function queryAll()
    {
        return $this->call(__FUNCTION__);
    }

    /**
     * 返回一列 (第一列)
     * @param int $columnNumber
     * @return array
     */
    public function queryColumn(int $columnNumber = 0)
    {
        return $this->call(__FUNCTION__, func_get_args());
    }

    /**
     * 返回一个标量值
     * @return mixed
     */
    public function queryScalar()
    {
        return $this->call(__FUNCTION__);
    }

    /**
     * 执行SQL语句
     * @return bool
     */
    public function execute()
    {
        return $this->call(__FUNCTION__);
    }

    /**
     * 开始事务
     * @return bool
     */
    public function beginTransaction()
    {
        return $this->call(__FUNCTION__);
    }

    /**
     * 执行方法
     * @param $name
     * @param array $arguments
     * @return mixed
     */
    protected function call($name, $arguments = [])
    {
        switch ($name) {
            case 'query':
            case 'queryOne':
            case 'queryAll':
            case 'queryColumn':
            case 'queryScalar':
                if ($this->inTransaction()) {
                    $this->switchToMasters();
                } else {
                    if ($this->_useMaster) {
                        $this->switchToMasters();
                    } else {
                        $this->switchToSlaves();
                    }
                }
                $this->reset();
                break;
            case 'execute':
                $this->switchToMasters();
                $this->reset();
                break;
            case 'beginTransaction':
                $this->switchToMasters();
                break;
        }
        return call_user_func_array("parent::{$name}", $arguments);
    }

    /**
     * 重置
     */
    protected function reset()
    {
        $this->_useMaster = null;
    }

    /**
     * 切换到主库群
     */
    protected function switchToMasters()
    {
        if (!isset($this->_pdoMaster)) {
            $this->dsn      = $this->masters[array_rand($this->masters)];
            $this->username = $this->masterConfig['username'];
            $this->password = $this->masterConfig['password'];
            parent::connect();
            $this->_pdoMaster = $this->_pdo;
        } else {
            $this->_pdo = $this->_pdoMaster;
        }
    }

    /**
     * 切换到从库群
     */
    protected function switchToSlaves()
    {
        if (!isset($this->_pdoSlave)) {
            $this->dsn      = $this->slaves[array_rand($this->slaves)];
            $this->username = $this->slaveConfig['username'];
            $this->password = $this->slaveConfig['password'];
            parent::connect();
            $this->_pdoSlave = $this->_pdo;
        } else {
            $this->_pdo = $this->_pdoSlave;
        }
    }

    /**
     * 检查是否在一个事务内
     * @return bool
     */
    public function inTransaction()
    {
        // 检查是否有Master连接，且在一个事务内
        if (isset($this->_pdoMaster) && $this->_pdoMaster->inTransaction()) {
            return true;
        }
        return false;
    }

}
