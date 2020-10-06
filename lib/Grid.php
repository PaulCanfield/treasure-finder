<?php

namespace Lib;

class Grid {
    // Contains the map data
    private $data;

    // Used for collection of valid routes.
    private $routes = [ ];

    // These functions are used as magic functions, they each define an offset for grid navigation.
    private $n = [-1, 0];
    private $ne = [-1, 1];
    private $e = [0, 1];
    private $se = [1, 1];
    private $s = [1, 0];
    private $sw = [1, -1];
    private $w = [0, -1];
    private $nw = [-1, -1];

    /**
     * Grid constructor.
     * @param array $data A 2 dimensional array, seeded.
     */
    public function __construct(Array $data) {
        $this->data = $data;
    }

    /**
     * Enable magic functions to make navigating easy. The function can be called
     * by calling the direction with coordinates.
     *
     * Magic methods are invoked when a function call is 1 or 2 characters and matches
     * an exiting property.
     *
     * ex. $grid->n(2,2)->value;
     *
     * @param $name
     * @param $arguments
     * @return Position|mixed|null
     */
    public function __call($name, $arguments) {
        if (strlen($name <= 2) && property_exists($this, $name)) {
            return $this->get($arguments[0], $arguments[1], $this->$name);
        } else {
            return call_user_func($this, $name, $arguments);
        }
    }

    /**
     *  Retrieve a Position object at a specific coordinate modified by offset, if out of bounds then returm null.
     *
     * @param int $x
     * @param int $y
     * @param int[] $offset x and y are offset by index 0 and 1 respectively
     * @return Position|null
     */
    public function get($x = 0, $y = 0, $offset = [0, 0]) {
        $x += $offset[0];
        $y += $offset[1];

        if (!isset($this->data[$x]) || !isset($this->data[$x][$y])) {
            return null;
        } else {
            return new Position($x, $y, $this->data[$x][$y]);
        }
    }

    /**
     * Example Output:
     *
     * [ D D O S O ]
     * [ D D S O D ]
     * [ O O S D O ]
     * [ O X O D O ]
     * [ D O O O O ]
     *
     * @return string
     */
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

    /**
     * Given a position find all open squares excluding positions found in the exclude argument.
     *
     * @param Position $position Current active position.
     * @param null $exclude Positions to exclude
     * @param string[] $directions List of directions defaults to cardinal directions.
     * @param string[] $safe Values of positions that are safe to pass through.
     * @return array List of safe exists from a space.
     */
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

    /**
     * Helper function to find all the starting positions marked with an 'S'.
     *
     * @return array|null A list of positions with value S
     */
    public function starts() {
        return $this->findLocations('S');
    }

    /**
     * Find the location of all positions matching search value.
     *
     * @param string $search value to search for.
     * @return array|null list of Positions that match search value.
     */
    public function findLocations(string $search) {
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

    /**
     * Find all routes based on the staring locations.
     *
     * @return array a list of Positions
     */
    public function findRoutes() {
        $positions = $this->starts();

        foreach ($positions as $index => $position) {
            $this->getValidRoutes($position);
        }

        return $this->routes;
    }

    /**
     * Recursive function to traverse the map and find all successful routes.
     *
     * @param $position
     * @param array $visited
     * @param array $path
     */
    public function getValidRoutes($position, $visited = [], $path = []) {
        if ($position->value == 'X') {
            $path[] = $position;
            $this->routes[] = $path;
            return;
        }

        $open = $this->findOpenSquares($position, $visited);
        foreach ($open as $key => $space) {
            $visited[] = $space;
            $this->getValidRoutes($space, $visited, array_merge($path, [ $position ]));
        }
    }
}
