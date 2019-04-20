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
    public static function buildWhere(array $where, int $id = 0)
    {
        $sql    = '';
        $params = [];
        foreach ($where as $key => $item) {
            if (count($item) == 3) {
                list($field, $operator, $condition) = $item;
                $prefix = "__{$id}_";
                $name   = $prefix . str_replace('.', '_', $field);
                $subSql = "{$field} {$operator} :{$name}";
                $sql    .= " AND {$subSql}";
                if ($key == 0) {
                    $sql = $subSql;
                }
                $params[$name] = $condition;
            }
            if (count($item) == 2) {
                list($symbol, $subWhere) = $item;
                if (in_array($symbol, ['or', 'and']) && is_array($subWhere)) {
                    if (count($subWhere) == count($subWhere, 1)) {
                        $subWhere = [$subWhere];
                    }
                    list($subSql, $subParams) = static::buildWhere($subWhere, ++$id);
                    if (count($subWhere) > 1) {
                        $subSql = "({$subSql})";
                    }
                    $sql    .= " " . strtoupper($symbol) . " {$subSql}";
                    $params = array_merge($params, $subParams);
                } else {
                    $sql    .= " AND {$subSql}";
                    if ($key == 0) {
                        $sql = $subSql;
                    }
                }
            }
        }
        return [$sql, $params];
    }

    /**
     * 构建Join条件
     * @param array $on
     * @param int $id
     * @return string
     */
    public static function buildJoinOn(array $on, int $id = 0)
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
                $subSql = static::buildJoinOn($subOn, ++$id);
                if (count($subOn) > 1) {
                    $subSql = "({$subSql})";
                }
                $sql .= " " . strtoupper($symbol) . " {$subSql}";
            }
        }
        return $sql;
    }

}
