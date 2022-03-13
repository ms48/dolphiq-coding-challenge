<?php

namespace Tests\Unit;

use App\Models\Plateau;
use App\Services\RoverCommunicationService;
use PHPUnit\Framework\TestCase;

class BaseUnit extends TestCase
{
    public function mockRoverCommunicationService()
    {
        return $this->createMock(RoverCommunicationService::class);
    }

    public function mockPlateau()
    {
        return $this->createMock(Plateau::class);
    }

}