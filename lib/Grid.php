<?php

namespace Lib;

class Grid {
    private $options = [ ];
    private $data;
    private $n = [-1, 0];
    private $ne = [-1, 1];
    private $e = [0, 1];
    private $se = [1, 1];
    private $s = [1, 0];
    private $sw = [1, -1];
    private $w = [0, -1];
    private $nw = [-1, -1];
    public $routes = [ ];

    public function __construct($data, $options = null) {
        $this->data = $data;
        $this->options = $options ?: $this->options;
    }

    public function __call($name, $arguments) {
        if (strlen($name <= 2) && property_exists($this, $name)) {
            return $this->get($arguments[0], $arguments[1], $this->$name);
        } else {
            return call_user_func($this, $name, $arguments);
        }
    }

    public function get($x = 0, $y = 0, $offset = [0, 0]) {
        $x += $offset[0];
        $y += $offset[1];

        if (!isset($this->data[$x]) || !isset($this->data[$x][$y])) {
            return null;
        } else {
            return new Position($x, $y, $this->data[$x][$y]);
        }
    }

    public function __toString() {
        $params = [];
        $str = '';
        foreach ($this->data as $x => $row) {
            $str .= '[ ';
            foreach ($row as $y => $value) {
                $str .= "%s ";
                $params[] = $value;
            }
            $str .= "]\n";
        }
        return vsprintf($str, $params);
    }

    public function findOpenSquares(Position $position, $exclude = null, $directions = ['n', 'e', 's', 'w'], $safe = [ 'S', 'O', 'X']) {
        $exits = [];
        foreach ($directions as $key => $method) {
            $valid = $this->$method($position->x, $position->y);

            if (is_array($exclude)) {
                if (array_search($valid, $exclude) !== false) {
                    continue;
                }
            }

            if ($valid && array_search($position->value, $safe) !== false) {
                $exits[] = $valid;
            }
        }
        return $exits;
    }

    public function starts() {
        return $this->findLocations('S');
    }

    public function findLocations($search) {
        $locations = [ ];
        foreach ($this->data as $x => $row) {
            foreach ($row as $y => $value) {
                if ($value == $search) {
                    $locations[] = $this->get($x, $y);
                }
            }
        }
        return count($locations) ? $locations : null;
    }

    public function findRoutes($positions = null, $visited = []) {
        $routes = [];
        $positions = $positions ?: $this->starts();

        foreach ($positions as $index => $position) {
            if ($position->value == 'D') {
                continue;
            }

            if (array_search($position, $visited) !== false) {
                continue;
            }

            if ($position->value == 'X') {
                $routes[(string) $position] = 'treasure';
                $visited[] = $position;
                $this->routes[] = $visited;
                continue;
            }

            $visited[] = $position;
            $open = $this->findOpenSquares($position);

            if ($open == null) {
                continue;
            }

            $routes[(string) $position] = $this->findRoutes($open, $visited);
        }

        return $routes;
    }
}
