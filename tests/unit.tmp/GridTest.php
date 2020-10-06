<?php

namespace Tests\Unit;

use Lib\Grid;
use Lib\GridGenerator;
use Lib\Position;
use Tests\BaseTest;

class GridTest extends BaseTest
{
    private array $staticGrid = [
        ['D', 'D', 'D', 'O', 'O'],
        ['O', 'S', 'O', 'O', 'O'],
        ['S', 'O', 'O', 'O', 'O'],
        ['D', 'O', 'X', 'D', 'X'],
        ['O', 'D', 'D', 'S', 'D']
    ];

    /** @test */
    public function given_a_grid_array_a_grid_object_can_be_initilized() {
        $this->assertInstanceOf(Grid::class, new Grid(GridGenerator::generateTreasureGrid()));
    }

    /** @test */
    public function given_a_grid_and_coordinates_a_position_object_can_be_retrieved_from_the_grid() {
        $grid = new Grid($this->staticGrid);

        $position = $grid->get(3, 4);
        $this->assertInstanceOf(Position::class, $position);
        $this->assertEquals('X', $position->value);
    }

    /** @test */
    public function given_a_grid_and_coordinates_and_offset_position_object_can_be_retrieved() {
        $grid = new Grid($this->staticGrid);

        $position = $grid->get(3, 4, [ 1, 0 ]);
        $this->assertInstanceOf(Position::class, $position);
        $this->assertEquals('D', $position->value);
    }

    /** @test */
    public function given_a_grid_out_of_bounds_coordinates_return_null() {
        $grid = new Grid($this->staticGrid);
        $this->assertNull($grid->get(10, 10));
    }

    /** @test */
    public function given_a_grid_magic_methods_work_to_return_positions_in_various_directions() {
        $grid = new Grid($this->staticGrid);

        $this->assertEquals($grid->n(2, 2)->value, 'O');
        $this->assertEquals($grid->ne(2, 2)->value, 'O');
        $this->assertEquals($grid->e(2, 2)->value, 'O');
        $this->assertEquals($grid->se(2, 2)->value, 'D');
        $this->assertEquals($grid->s(2, 2)->value, 'X');
        $this->assertEquals($grid->sw(2, 2)->value, 'O');
        $this->assertEquals($grid->w(2, 2)->value, 'O');
        $this->assertEquals($grid->nw(2, 2)->value, 'S');
    }

    /** @test */
    public function given_a_grid_it_can_be_converted_to_a_string() {
        $grid = new Grid($this->staticGrid);
        $expectedOutput = "[ D D D O O ]\n"."[ O S O O O ]\n"."[ S O O O O ]\n"."[ D O X D X ]\n"."[ O D D S D ]\n";

        $this->assertEquals($expectedOutput, (string) $grid);
    }

    /** @test */
    public function given_a_grid_and_a_position_all_open_squares_can_be_located() {
        $grid = new Grid($this->staticGrid);
        $position = $grid->get(2, 2);

        $positions = $grid->findOpenSquares($position);
        $this->assertCount(4, $positions);
    }

    /** @test */
    public function given_a_grid_and_a_position_and_an_exclude_list_all_open_squares_can_be_located() {
        $grid = new Grid($this->staticGrid);
        $position = $grid->get(2, 2);
        $exclude = [ $grid->n(2, 2) ];

        $positions = $grid->findOpenSquares($position, $exclude);
        $this->assertCount(3, $positions);
    }

    /** @test */
    public function given_a_grid_find_all_the_starting_locations() {
        $grid = new Grid($this->staticGrid);
        $this->assertCount(3, $grid->starts());
    }

    /** @test */
    public function given_a_grid_find_all_matching_locations() {
        $grid = new Grid($this->staticGrid);
        $this->assertCount(8, $grid->findLocations('D'));
    }

    /** @test */
    public function given_a_grid_find_all_valid_routes() {
        $grid = new Grid($this->staticGrid);
        $this->assertCount(51, $grid->findRoutes());
    }

    /** @test */
    public function given_a_grid_the_first_shortest_route_can_be_found() {
        $grid = new Grid($this->staticGrid);
        $shortestRoute = $grid->findShortestRoute();

        $this->assertCount(4, $shortestRoute);
        $string = '';
        foreach($shortestRoute as $key => $value) {
            $string .= (string) $value;
        }
        $this->assertEquals('(1, 1)(1, 2)(2, 2)(3, 2)', $string);
    }
}
