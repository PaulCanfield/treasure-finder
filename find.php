<?php

require './vendor/autoload.php';

use Lib\GridGenerator;
use Lib\Grid;

$config = include('config/config.default.php');
if (file_exists('config/config.php')) {
    $config = array_merge($config, include('config/config.php'));
}

$grid = new Grid(
    GridGenerator::generateTreasureGrid(
        $config['x'],
        $config['y'],
        $config['seed']
    )
);

echo $grid;
echo "\n";

$shortestRoute = $grid->findShortestRoute();
if (!$shortestRoute) {
    echo "No valid routes found!";
} else {
    foreach ($grid->findShortestRoute() as $index => $position) {
        echo $position;
    }
}
echo "\n";
