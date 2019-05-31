<?php

namespace Mix\Database;

/**
 * Interface QueryListenerInterface
 * @package Mix\Database
 * @author liu,jian <coder.keda@gmail.com>
 */
interface QueryListenerInterface
{

    /**
     * 监听
     * @param $query
     * @return mixed
     */
    public function listen($query);

}
