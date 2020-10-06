<?php

namespace Lib;

class GridGenerator {
    static $defaultOptions = [
        'S' => 3,
        'X' => 1,
        'D' => 8
    ];

    static public function generateTreasureGrid($x = 5, $y = 5, $options = null) {
        $grid = array_fill(0, $x, array_fill(0, $y, 'O'));

        foreach ($options ?: static::$defaultOptions as $item => $occurrences) {
            while ($occurrences > 0) {
                $grid = static::placeItem($grid, $item);
                $occurrences--;
            }
        }
        return $grid;
    }

    static private function placeItem($grid, $item) {
        $x = array_rand($grid);
        $y = array_rand($grid[0]);

        if ($grid[$x][$y] == 'O') {
            $grid[$x][$y] = $item;
        } else {
            $grid = static::placeItem($grid, $item);
        }
        return $grid;
    }
}
