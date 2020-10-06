<?php

require './vendor/autoload.php';

use Lib\GridGenerator;
use Lib\Grid;
use Lib\Position;

$grid = new Grid(GridGenerator::generateTreasureGrid());

echo $grid;
echo "\n";

$grid->findRoutes($grid->starts());

foreach ($grid->routes as $index => $route) {
    foreach ($route as $index2 => $position) {
        echo $position;
    }
    echo "\n";
}




