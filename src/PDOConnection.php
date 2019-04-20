<?php

namespace Mix\Database;

use Mix\Database\Base\AbstractPDOConnection;

/**
 * Class PDOConnection
 * @package Mix\Database
 * @author liu,jian <coder.keda@gmail.com>
 */
class PDOConnection extends AbstractPDOConnection
{

    /**
     * 后置处理事件
     */
    public function onAfterInitialize()
    {
        parent::onAfterInitialize();
        // 关闭连接
        $this->disconnect();
    }

    /**
     * 析构事件
     */
    public function onDestruct()
    {
        parent::onDestruct();
        // 关闭连接
        $this->disconnect();
    }

}
