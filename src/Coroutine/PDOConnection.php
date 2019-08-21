<?php

namespace Mix\Database\Coroutine;

use Mix\Pool\ConnectionTrait;

/**
 * Class PDOConnection
 * @package Mix\Database\Coroutine
 * @author liu,jian <coder.keda@gmail.com>
 */
class PDOConnection extends \Mix\Database\Persistent\PDOConnection
{

    use ConnectionTrait;

    /**
     * 释放连接
     * @return bool
     */
    public function release()
    {
        if (isset($this->connectionPool)) {
            if ($this->inTransaction()) {
                return false;
            }
            return $this->connectionPool->release($this);
        }
        return false;
    }

    /**
     * 析构
     */
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
        // 丢弃连接
        $this->discard();
    }

}
