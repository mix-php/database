<?php

namespace Mix\Database;

use Mix\Pool\ConnectionTrait;

/**
 * Class Connection
 * @package Mix\Database
 * @author liu,jian <coder.keda@gmail.com>
 */
class Connection extends \Mix\Database\Persistent\Connection
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
        // 丢弃连接
        $this->discard();
    }

}
