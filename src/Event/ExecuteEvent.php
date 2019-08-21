<?php

namespace Mix\Database\Event;

/**
 * Class ExecuteEvent
 * @package Mix\Database\Event
 * @author liu,jian <coder.keda@gmail.com>
 */
class ExecuteEvent
{

    /**
     * @var string
     */
    public $sql = '';

    /**
     * @var array
     */
    public $bindings = [];

    /**
     * @var float
     */
    public $time = 0;

}
