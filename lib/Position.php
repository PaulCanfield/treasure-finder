<?php

namespace Lib;

class Position {
    public $x;
    public $y;
    public $value;
    public $visited;

    public function __construct($x, $y, $value = 0, $visited = false) {
        $this->x = $x;
        $this->y = $y;
        $this->value = $value;
        $this->visited = $visited;
    }

    public function __toString() {
        return sprintf('(%s, %s)', $this->x, $this->y);
    }
}
