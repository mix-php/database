<?php

namespace Mix\Database\Coroutine;

use Mix\Pool\ConnectionTrait;

/**
 * PDOCoroutine组件
 * @author liu,jian <coder.keda@gmail.com>
 */
class PDOConnection extends \Mix\Database\Persistent\PDOConnection
{

    use ConnectionTrait;

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
