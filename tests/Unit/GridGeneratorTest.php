<?php

namespace Tests\Unit;

use Lib\GridGenerator;
use Tests\BaseTest;

class GridGeneratorTest extends BaseTest
{
    /** @test */
    public function a_grid_generator_will_can_create_an_array() {
        $this->assertIsArray(GridGenerator::generateTreasureGrid());
    }

    /** @test */
    public function default_size_will_be_five_by_five() {
        $grid = GridGenerator::generateTreasureGrid();

        $this->assertEquals(5, count($grid));
        $this->assertEquals(5, count($grid[0]));
    }

    /** @test */
    public function a_grid_can_be_created_for_a_specific_size() {
        $grid = GridGenerator::generateTreasureGrid(10, 10);

        $this->assertEquals(10, count($grid));
        $this->assertEquals(10, count($grid[0]));
    }

    /** @test */
    public function a_grid_will_have_the_correct_number_of_objects() {
        $options = [
            'S' => 5,
            'X' => 2,
            'D' => 5
        ];

        $grid = GridGenerator::generateTreasureGrid(5, 5, $options);
        foreach ($options as $key => $value) {
            $this->assertEquals($value, $this->countElement($key, $grid));
        }
    }

    /**
     * Helper function to count the number of a specific element in the grid.
     *
     * @param $element
     * @param $grid
     */
    private function countElement($element, $grid) {
        $count = 0;
        foreach ($grid as $x => $row) {
            foreach($row as $y => $value) {
                if ($value == $element) {
                    $count++;
                }
            }
        }
        return $count;
    }
}
