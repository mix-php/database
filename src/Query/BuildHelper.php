<?php

namespace Mix\Database\Query;

/**
 * Class BuildHelper
 * @package Mix\Database\Query
 * @author liu,jian <coder.keda@gmail.com>
 */
class BuildHelper
{

    /**
     * 构建数据
     * @param array $data
     * @return array
     */
    public static function buildData(array $data)
    {
        $sql    = [];
        $params = [];
        foreach ($data as $key => $item) {
            if (is_array($item)) {
                list($operator, $value) = $item;
                $sql[]        = "`{$key}` =  `{$key}` {$operator} :{$key}";
                $params[$key] = $value;
                continue;
            }
            $sql[]        = "`{$key}` = :{$key}";
            $params[$key] = $item;
        }
        return [implode(', ', $sql), $params];
    }

    /**
     * 构建条件
     * @param array $where
     * @param int $id
     * @return array
     */
    public static function buildWhere(array $where, &$id = null)
    {
        $sql    = '';
        $params = [];
        foreach ($where as $key => $item) {
            $id++;
            $length = count($item);
            if ($length == 2) {
                // 子条件
                if (in_array($item[0], ['or', 'and']) && is_array($item[1])) {
                    list($symbol, $subWhere) = $item;
                    if (count($subWhere) == count($subWhere, 1)) {
                        $subWhere = [$subWhere];
                    }
                    list($subSql, $subParams) = static::buildWhere($subWhere, $id);
                    if (count($subWhere) > 1) {
                        $subSql = "({$subSql})";
                    }
                    $sql    .= " " . strtoupper($symbol) . " {$subSql}";
                    $params = array_merge($params, $subParams);
                }
                // 无值条件
                if (is_string($item[0]) && is_string($item[1])) {
                    list($field, $operator) = $item;
                    $subSql = "{$field} {$operator}";
                    $sql    .= " AND {$subSql}";
                    if ($key == 0) {
                        $sql = $subSql;
                    }
                }
            }
            if ($length == 3) {
                // 标准条件 (包含In/NotIn)
                list($field, $operator, $condition) = $item;
                if (
                    (is_string($field) && is_string($operator) && is_scalar($condition)) ||
                    (is_string($field) && in_array(strtoupper($operator), ['IN', 'NOT IN']) && is_array($condition))
                ) {
                    $prefix   = "__{$id}_";
                    $name     = $prefix . str_replace('.', '_', $field);
                    $operator = strtoupper($operator);
                    if (!is_array($condition)) {
                        $subSql = "{$field} {$operator} :{$name}";
                    } else {
                        $subSql = "{$field} {$operator} (:{$name})";
                    }
                    $sql .= " AND {$subSql}";
                    if ($key == 0) {
                        $sql = $subSql;
                    }
                    $params[$name] = $condition;
                }
            }
            if ($length == 4) {
                // Between/NotBetween
                list($field, $operator, $condition1, $condition2) = $item;
                if (
                    is_string($field) &&
                    in_array(strtoupper($operator), ['BETWEEN', 'NOT BETWEEN']) &&
                    is_scalar($condition1) &&
                    is_scalar($condition2)
                ) {
                    $prefix   = "__{$id}_";
                    $name1    = $prefix . '1_' . str_replace('.', '_', $field);
                    $name2    = $prefix . '2_' . str_replace('.', '_', $field);
                    $operator = strtoupper($operator);
                    $subSql   = "{$field} {$operator} :{$name1} AND :{$name2}";
                    $sql      .= " AND {$subSql}";
                    if ($key == 0) {
                        $sql = $subSql;
                    }
                    $params[$name1] = $condition1;
                    $params[$name2] = $condition2;
                }
            }
        }
        return [$sql, $params];
    }

    /**
     * 构建Join条件
     * @param array $on
     * @return string
     */
    public static function buildJoinOn(array $on)
    {
        $sql = '';
        if (count($on) == count($on, 1)) {
            $on = [$on];
        }
        foreach ($on as $key => $item) {
            if (count($item) == 3) {
                list($field, $operator, $condition) = $item;
                $subSql = "{$field} {$operator} {$condition}";
                $sql    .= " AND {$subSql}";
                if ($key == 0) {
                    $sql = $subSql;
                }
                continue;
            }
            if (count($item) == 2) {
                list($symbol, $subOn) = $item;
                if (!in_array($symbol, ['or', 'and'])) {
                    continue;
                }
                if (count($subOn) == count($subOn, 1)) {
                    $subOn = [$subOn];
                }
                $subSql = static::buildJoinOn($subOn);
                if (count($subOn) > 1) {
                    $subSql = "({$subSql})";
                }
                $sql .= " " . strtoupper($symbol) . " {$subSql}";
            }
        }
        return $sql;
    }

}
