<?php

require './vendor/autoload.php';

use Lib\GridGenerator;
use Lib\Grid;

$grid = new Grid(GridGenerator::generateTreasureGrid());

echo $grid;
echo "\n";

$shortest = null;
foreach ($grid->findRoutes() as $index => $route) {
    if ($shortest == null || count($shortest) > count($route)) {
        $shortest = $route;
    }
}

foreach ($route as $index => $position) {
    echo $position;
}
echo "\n";
