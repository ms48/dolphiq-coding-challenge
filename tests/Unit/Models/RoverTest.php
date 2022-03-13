<?php

namespace Tests\Unit\Models;

use App\Exceptions\InvalidInputException;
use App\Models\Plateau;
use App\Models\Rover;
use Tests\Unit\BaseUnit;

class RoverTest extends BaseUnit
{
    protected Rover $rover;

    public function setUp(): void
    {
        $this->rover = new Rover(1, 2, 'N', new Plateau([5, 5]));
    }

    public function testCommandsGetterAndSetter()
    {
        $this->rover->setCommands('MM');
        $this->assertEquals(['M', 'M'], $this->rover->getCommands());
    }

    public function testSetCommandsMethodValidatingTheInputs()
    {
        //check empty
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessageMatches('/^Invalid Rover Command Input/');
        $this->rover->setCommands('');

        //check invalid value
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessageMatches('/^Invalid Rover Command Input/');
        $this->rover->setCommands('MMS');
    }
    
    public function testXVariablesGetterAndSetter() 
    {
        $this->rover->setX(2);
        $this->assertEquals(2, $this->rover->getX());
    }

    public function testSetXMethodValidatingTheInputs()
    {
        //check if plateau upper right coordinate exceeded
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessageMatches('/^Mayday! Can\'t move the rover further. The X/');
        $this->rover->setX(6);

        //check if plateau lower left coordinate exceeded
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessageMatches('/^Mayday! Can\'t move the rover further. The X/');
        $this->rover->setX(-1);

        //check the border values
        $this->rover->setX(5);
        $this->assertEquals(5, $this->rover->getX());

        $this->rover->setX(0);
        $this->assertEquals(0, $this->rover->getX());
    }

    public function testYVariablesGetterAndSetter()
    {
        $this->rover->setY(2);
        $this->assertEquals(2, $this->rover->getY());
    }

    public function testSetYMethodValidatingTheInputs()
    {
        //check if plateau upper right coordinate exceeded
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessageMatches('/^Mayday! Can\'t move the rover further. The Y/');
        $this->rover->setY(6);

        //check if plateau lower left coordinate exceeded
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessageMatches('/^Mayday! Can\'t move the rover further. The Y/');
        $this->rover->setY(-1);

        //check the border values
        $this->rover->setY(5);
        $this->assertEquals(5, $this->rover->getY());

        $this->rover->setY(0);
        $this->assertEquals(0, $this->rover->getY());
    }

    public function testOrientationVariablesGetterAndSetter()
    {
        $this->rover->setOrientation('N');
        $this->assertEquals('N', $this->rover->getOrientation());
    }

    public function testSetOrientationMethodValidatingTheInputs()
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessageMatches('/^Invalid orientation/');
        $this->rover->setOrientation(6);
    }

    public function testGetPositionMethodWillReturnCorrectString()
    {
        $position = $this->rover->getPosition();
        $this->assertEquals('1 2 N', $position);
    }

    public function testNavigateMethodWillTurnLeft(){
        //check 1 turn
        $this->rover->setCommands('L');
        $this->rover->navigate();
        $this->assertEquals('1 2 W', $this->rover->getPosition());

        //check multiple
        $this->rover->setCommands('LLL');
        $this->rover->setOrientation('N');
        $this->rover->navigate();
        $this->assertEquals('1 2 E', $this->rover->getPosition());
    }

    public function testNavigateMethodWillTurnRight(){
        //check 1 turn
        $this->rover->setCommands('R');
        $this->rover->navigate();
        $this->assertEquals('1 2 E', $this->rover->getPosition());

        //check multiple
        $this->rover->setCommands('RRR');
        $this->rover->setOrientation('N');
        $this->rover->navigate();
        $this->assertEquals('1 2 W', $this->rover->getPosition());
    }
    
    public function testNavigateMethodWillMoveTheRover()
    {
        $this->rover->setCommands('M');
        
        $this->rover->navigate();
        $this->assertEquals('1 3 N', $this->rover->getPosition());

        //test move south
        $this->rover->setOrientation('S');
        $this->rover->navigate();
        $this->assertEquals('1 2 S', $this->rover->getPosition());

        //test move east
        $this->rover->setOrientation('E');
        $this->rover->navigate();
        $this->assertEquals('2 2 E', $this->rover->getPosition());

        //test move west
        $this->rover->setOrientation('W');
        $this->rover->navigate();
        $this->assertEquals('1 2 W', $this->rover->getPosition());
    }

    public function testNavigateMethodWillThrowErrorWhenTryingToMoveOutsideThePlateau()
    {
        $rover = new Rover(2, 3, 'N', new Plateau([3, 3]));
        $rover->setCommands('M');
        
        //north boundary
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessageMatches('/^Mayday! Can\'t move the rover further./');
        $rover->navigate();

        //south boundary
        $rover->setOrientation('S');
        $rover->setY(0);
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessageMatches('/^Mayday! Can\'t move the rover further./');
        $rover->navigate();

        //east boundary
        $rover->setOrientation('E');
        $rover->setX(3);
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessageMatches('/^Mayday! Can\'t move the rover further./');
        $rover->navigate();

        //west boundary
        $rover->setOrientation('W');
        $rover->setX(0);
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessageMatches('/^Mayday! Can\'t move the rover further./');
        $rover->navigate();
    }
    
    public function testNavigateMethodWillMoveAndTurn()
    {
        $this->rover->setCommands('MRRMMLM');
        $this->rover->navigate();
        $this->assertEquals('2 1 E', $this->rover->getPosition());
    }
}