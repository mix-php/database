<?php

namespace Mix\Database\Pool;

use Mix\Pool\DialInterface;

/**
 * Class Dial
 * @package Mix\Database\Pool
 * @author liu,jian <coder.keda@gmail.com>
 */
class Dial implements DialInterface
{

    /**
     * 处理
     * @return \Mix\Database\Coroutine\PDOConnection
     */
    public function handle()
    {
        return \Mix\Database\Coroutine\PDOConnection::newInstance();
    }

}
