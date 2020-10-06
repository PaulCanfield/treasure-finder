<?php

namespace Tests\Unit;

use Lib\Position;
use Tests\BaseTest;

class PositionTest extends BaseTest
{
    /** @test */
    public function a_position_can_be_have_an_x_value() {
        $position = new Position(2, 2);
        $this->assertEquals(2, $position->x);
    }

    /** @test */
    public function a_position_can_be_have_an_y_value() {
        $position = new Position(2, 2);
        $this->assertEquals(2, $position->y);
    }

    /** @test */
    public function a_position_can_be_have_an_value() {
        $position = new Position(2, 2, 'X');
        $this->assertEquals('X', $position->value);
    }

    /** @test */
    public function a_position_can_be_converted_to_a_string() {
        $position = new Position(2, 2);
        $this->assertEquals("(2, 2)", (string) $position);
    }
}
