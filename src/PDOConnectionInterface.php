<?php

namespace Mix\Database;

/**
 * Interface PDOConnectionInterface
 * @package Mix\Database
 * @author liu,jian <coder.keda@gmail.com>
 */
interface PDOConnectionInterface
{

    /**
     * 关闭连接
     * @return bool
     */
    public function disconnect();

    /**
     * 查询构建
     * @param $item
     * @return $this
     */
    public function queryBuilder($item);

    /**
     * 创建命令
     * @param null $sql
     * @return $this
     */
    public function createCommand($sql = null);

    /**
     * 绑定参数
     * @param $data
     * @return $this
     */
    public function bindParams($data);

    /**
     * 返回结果集
     * @return \PDOStatement
     */
    public function query();

    /**
     * 返回一行
     * @return mixed
     */
    public function queryOne();

    /**
     * 返回多行
     * @return array
     */
    public function queryAll();

    /**
     * 返回一列 (第一列)
     * @param int $columnNumber
     * @return array
     */
    public function queryColumn($columnNumber = 0);

    /**
     * 返回一个标量值
     * @return mixed
     */
    public function queryScalar();

    /**
     * 执行SQL语句
     * @return bool
     */
    public function execute();

    /**
     * 返回最后插入行的ID或序列值
     * @return string
     */
    public function getLastInsertId();

    /**
     * 返回受上一个 SQL 语句影响的行数
     * @return int
     */
    public function getRowCount();

    /**
     * 返回原生SQL语句
     * @return string
     */
    public function getRawSql();

    /**
     * 插入
     * @param string $table
     * @param array $data
     * @return $this
     */
    public function insert(string $table, array $data);

    /**
     * 批量插入
     * @param string $table
     * @param array $data
     * @return $this
     */
    public function batchInsert(string $table, array $data);

    /**
     * 更新
     * @param string $table
     * @param array $data
     * @param array $where
     * @return $this
     */
    public function update(string $table, array $data, array $where);

    /**
     * 删除
     * @param string $table
     * @param array $where
     * @return $this
     */
    public function delete(string $table, array $where);

    /**
     * 自动事务
     * @param $closure
     * @throws \Throwable
     */
    public function transaction($closure);

    /**
     * 开始事务
     * @return bool
     */
    public function beginTransaction();

    /**
     * 提交事务
     * @return bool
     */
    public function commit();

    /**
     * 回滚事务
     * @return bool
     */
    public function rollback();

}
