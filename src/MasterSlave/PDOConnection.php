<?php

namespace Mix\Database\MasterSlave;

/**
 * PdoMasterSlave组件
 * @author LIUJIAN <coder.keda@gmail.com>
 */
class PDOConnection extends BasePDOConnection
{

    // 后置处理事件
    public function onAfterInitialize()
    {
        parent::onAfterInitialize();
        // 关闭连接
        $this->disconnect();
    }

    // 析构事件
    public function onDestruct()
    {
        parent::onDestruct();
        // 关闭连接
        $this->disconnect();
    }

}
