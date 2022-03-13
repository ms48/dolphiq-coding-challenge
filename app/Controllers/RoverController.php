<?php

namespace App\Controllers;

use App\Exceptions\InvalidInputException;
use App\Services\RoverCommunicationService;
use PHPUnit\Exception;

class RoverController extends BaseController
{

    public function __construct(private RoverCommunicationService $roverCommunicateService)
    {
    }

    /**
     * Render main view
     */
    public function index()
    {
        return $this->renderView('main');
    }

    /**'
     * Send commands to the rovers
     */
    public function send()
    {
        //get commands and sanitize
        $commands = trim(filter_input(INPUT_POST, 'commands', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        if (!$commands) {
            $this->respond('No commands were found to send.', 422);
        }

        //process the commands
        try {
            $response = $this->roverCommunicateService->sendInstructions($commands);
            if (!$response) {
                $this->respond('Something went wrong while sending data.', 500);
            }

            return $this->respond(['data' => $response]);
        } catch (InvalidInputException $e) {
            $this->respond($e->getMessage(), 422);
        } catch (Exception $e) {
            $this->respond('Something went wrong while sending data', 500);
        }

    }
}