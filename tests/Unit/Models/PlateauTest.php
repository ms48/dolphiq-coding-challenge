<?php

namespace Tests\Unit\Models;

use App\Models\Plateau;
use Tests\Unit\BaseUnit;

class PlateauTest extends BaseUnit {

    protected Plateau $plateau;

    public function setUp(): void
    {
        $this->plateau = new Plateau([5, 5]);
    }
    
    public function testCheckBoundariesAreExceedingMethodWillValidateAsExpected()
    {
        //valid value
        $this->assertEquals(false, $this->plateau->checkBoundariesAreExceeding(1, 2));
        
        //borders
        $this->assertEquals(false, $this->plateau->checkBoundariesAreExceeding(5, 2));
        $this->assertEquals(false, $this->plateau->checkBoundariesAreExceeding(2, 5));
        $this->assertEquals(false, $this->plateau->checkBoundariesAreExceeding(5, 5));
        $this->assertEquals(false, $this->plateau->checkBoundariesAreExceeding(0, 0));
        
        //exceeded the upper right
        $this->assertEquals(true, $this->plateau->checkBoundariesAreExceeding(6, 2));
        $this->assertEquals(true, $this->plateau->checkBoundariesAreExceeding(2, 6));

        //exceeded the lower left
        $this->assertEquals(true, $this->plateau->checkBoundariesAreExceeding(-1, 2));
        $this->assertEquals(true, $this->plateau->checkBoundariesAreExceeding(2, -1));
    }
}