<?php

namespace Mix\Database\Pool;

use Mix\Pool\DialInterface;

/**
 * Class PDOConnectionDial
 * @author LIUJIAN <coder.keda@gmail.com>
 * @package Mix\Database\Coroutine
 */
class Dial implements DialInterface
{

    /**
     * 拨号
     * @return PDOConnection
     */
    public function handle()
    {
        return \Mix\Database\Coroutine\PDOConnection::newInstance();
    }

}
