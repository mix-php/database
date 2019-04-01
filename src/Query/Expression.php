<?php
/**
 * @author ihipop@gmail.com @ 19-3-28 上午10:36 For mix-v2.
 */

namespace Mix\Database\Query;

class Expression
{

    /**
     * The value of the expression.
     *
     * @var mixed
     */
    protected $value;

    /**
     * Create a new raw query expression.
     *
     * @param  mixed $value
     *
     * @return void
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Get the value of the expression.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get the value of the expression.
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getValue();
    }
}
