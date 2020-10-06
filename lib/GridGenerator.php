<?php

namespace Lib;

class GridGenerator
{
    // The default number of instances for various objects in map.
    public static array $defaultOptions = [
        'S' => 3,
        'X' => 2,
        'D' => 8
    ];

    public static string $openSpace = 'O';

    public static int $defaultX = 5;
    public static int $defaultY = 5;

    /**
     * Create an array map and seed it with objects in accordance with the default options.
     *
     * @param int $x number of rows
     * @param int $y number of columns
     * @param null $options for overriding the default options.
     * @return array multi-dimensional array seeded according to options.
     */
    static public function generateTreasureGrid($x = null, $y = null, $options = null) {
        $x = $x ?: static::$defaultX;
        $y = $y ?: static::$defaultY;

        $grid = array_fill(0, $x, array_fill(0, $y, static::$openSpace));

        foreach ($options ?: static::$defaultOptions as $item => $occurrences) {
            while ($occurrences > 0) {
                $grid = static::placeItem($grid, $item);
                $occurrences--;
            }
        }
        return $grid;
    }

    /**
     * Recursively find an empty location for item.
     *
     * @param array $grid to add item to
     * @param string $item to be inserted into the grid
     * @return array grid with item inserted
     */
    static private function placeItem(array $grid, string $item) {
        $x = array_rand($grid);
        $y = array_rand($grid[0]);

        if ($grid[$x][$y] == static::$openSpace) {
            $grid[$x][$y] = $item;
        } else {
            $grid = static::placeItem($grid, $item);
        }
        return (array) $grid;
    }
}
