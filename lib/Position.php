<?php

namespace Lib;

class Position
{
    public $x;
    public $y;
    public $value;

    /**
     * Position constructor.
     * @param $x
     * @param $y
     * @param int $value
     */
    public function __construct($x, $y, $value = 0) {
        $this->x = $x;
        $this->y = $y;
        $this->value = $value;
    }

    /**
     * Example Output:
     * (2, 1)
     *
     * @return string
     */
    public function __toString() {
        return sprintf('(%s, %s)', $this->x, $this->y);
    }
}
