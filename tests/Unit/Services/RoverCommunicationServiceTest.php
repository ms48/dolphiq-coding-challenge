<?php

namespace Tests\Unit;

use App\Exceptions\InvalidInputException;
use App\Services\RoverCommunicationService;

class RoverCommunicationServiceTest extends BaseUnit {

    protected RoverCommunicationService $service;

    public function setUp(): void
    {
        $this->service = new RoverCommunicationService();
    }
    
    public function testSendInstructionsMethodWillReturnArray()
    {
        //Two rovers
        $response = $this->service->sendInstructions("5 5\n1 2 N\nLMLMLMLMM\n3 3 E\nMMRMMRMRRM");
        $this->assertIsArray($response);
    }

    public function testSendInstructionsMethodWillReturnExceptionForInvalidInputs()
    {
        //commands is empty
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessageMatches('/^Please provide sufficient commands for at least one/');
        $response = $this->service->sendInstructions("");
        
        //Insufficient commands to move
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessageMatches('/^Please provide sufficient commands for at least one/');
        $response = $this->service->sendInstructions("5 5\n1 2 N\n");

        //invalid rover coordinates
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessageMatches('/^Invalid Rover coordinates/');
        $response = $this->service->sendInstructions("5 5\n1 s F\nLMLMLMLMM\n3 3 E\nMMRMMRMRRM");

        //invalid plateau coordinates
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessageMatches('/^Invalid coordinates for plateau/');
        $response = $this->service->sendInstructions("5 a\n1 1 N\nLMLMLMLMM\n3 3 E\nMMRMMRMRRM");
    }
}