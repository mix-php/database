<?php

namespace Mix\Database;

/**
 * Interface PDOConnectionInterface
 * @package Mix\Database
 * @author LIUJIAN <coder.keda@gmail.com>
 */
interface PDOConnectionInterface
{

    // 关闭连接
    public function disconnect();

    // 查询构建
    public function queryBuilder($item);

    // 创建命令
    public function createCommand($sql = null);

    // 绑定参数
    public function bindParams($data);

    /**
     * 返回结果集
     * @return \PDOStatement
     */
    public function query();

    // 返回一行
    public function queryOne();

    // 返回多行
    public function queryAll();

    // 返回一列 (第一列)
    public function queryColumn($columnNumber = 0);

    // 返回一个标量值
    public function queryScalar();

    // 执行SQL语句
    public function execute();

    // 返回最后插入行的ID或序列值
    public function getLastInsertId();

    // 返回受上一个 SQL 语句影响的行数
    public function getRowCount();

    // 返回原生SQL语句
    public function getRawSql();

    // 插入
    public function insert($table, $data);

    // 批量插入
    public function batchInsert($table, $data);

    // 更新
    public function update($table, $data, $where);

    // 删除
    public function delete($table, $where);

    // 自动事务
    public function transaction($closure);

    // 开始事务
    public function beginTransaction();

    // 提交事务
    public function commit();

    // 回滚事务
    public function rollback();

}
