<?php

namespace App\Services;

use App\Constants\OrientationsConst;
use App\Exceptions\InvalidInputException;
use App\Models\Plateau;
use App\Models\Rover;

class RoverCommunicationService
{

    /**
     * @var Plateau
     */
    protected Plateau $plateau;

    /**
     * Send instructions to the rover
     * @param string $inputCommands
     * @return array
     */
    public function sendInstructions(string $inputCommands): array
    {
        //get commands
        $commandArray = $this->separateLines($inputCommands);
        //check minimum lines entered
        if (count($commandArray) < 3) {
            throw new InvalidInputException("Please provide sufficient commands for at least one rover to move!");
        }

        //resolve plateau data and process.
        $this->plateau = $this->createPlateauGrid(array_shift($commandArray));


        //The rest of the data should be rover related data
        $response = [];
        $counter = 1;
        $rover = null;
        foreach ($commandArray as $commands) {
            //odd line should be rover position and the even lines should be direction commands
            if ($counter % 2 === 1) {
                //odd
                $rover = $this->initRoverByCommand($commands);
            } else {
                //even
                $rover->setCommands($commands);
                $rover->navigate();
                array_push($response, $rover->getPosition());
            }
            $counter++;
        }
        // return the response
        return $response;
    }

    /**
     * Init a rover
     * @param string $coordinatesWithOrientation
     * @return Rover
     */
    protected function initRoverByCommand(string $coordinatesWithOrientation): Rover
    {
        $coordinatesWithOrientation = strtoupper(trim($coordinatesWithOrientation));
        //validate input
        if (!preg_match(
            "/^[\\d][\\s][\\d][\\s][" . implode('', array_keys(OrientationsConst::ORIENTATIONS_CONFIG)) . "]$/",
            $coordinatesWithOrientation
        )) {
            throw new InvalidInputException("Invalid Rover coordinates: {$coordinatesWithOrientation}");
        }

        $data = explode(" ", $coordinatesWithOrientation);

        return new Rover((int)$data[0], (int)$data[1], $data[2], $this->plateau);
    }

    /**
     * Separate commands as array
     * @param string $text
     * @return array
     */
    protected function separateLines(string $text): array
    {
        return explode("\n", str_replace("\r", "", $text));
    }

    /**
     * Create a Plateau
     * @param string $coordinates
     * @return Plateau
     */
    protected function createPlateauGrid(string $coordinates): Plateau
    {
        //validate inputs
        if (empty($coordinates)) {
            throw new InvalidInputException("Please provide the coordinates for the plateau!");
        }

        $data = explode(' ', $coordinates);
        if (count($data) !== 2 || (int)$data[0] < 1 || (int)$data[1] < 1) {
            throw new InvalidInputException("Invalid coordinates for plateau: {$coordinates}");
        }
        //create
        return new Plateau([(int)$data[0], (int)$data[1]]);
    }

}