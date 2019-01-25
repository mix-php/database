<?php

namespace Mix\Database\Pool;

use Mix\Pool\DialInterface;

/**
 * Class Dial
 * @author LIUJIAN <coder.keda@gmail.com>
 * @package Mix\Database\Pool
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
