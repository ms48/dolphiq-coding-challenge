<?php

namespace App\Models;

use App\Constants\CommandConst;
use App\Constants\OrientationsConst;
use App\Exceptions\InvalidInputException;
use App\Models\Commands\NavigatorFactory;

class Rover
{
    /**
     * @var array Commands Array
     */
    protected array $commands;

    /**
     * @var int 
     */
    protected int $x = 0;

    /**
     * @var int 
     */
    protected int $y = 0;

    /**
     * @var string 
     */
    protected string $orientation = 'N';

    public function __construct(
        int $x,
        int $y,
        string $orientation,
        protected Plateau $plateau
    ) {
        //validate and set the values
        $this->setX($x);
        $this->setY($y);
        $this->setOrientation($orientation);
    }

    /**
     * Get Commands as array
     * @return array
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * Command setter
     * @param string $inputCommands
     */
    public function setCommands(string $inputCommands): void
    {
        $inputCommands = strtoupper(trim($inputCommands));
        //validate input
        if (!preg_match("/^([". implode('', CommandConst::VALID_COMMANDS) ."]{1,})$/", $inputCommands)) {
            throw new InvalidInputException("Invalid Rover Command Input: {$inputCommands}");
        }

        $this->commands = str_split(trim(strtoupper($inputCommands)));
    }

    /**
     * Get X coordinate of the rover
     * @return int
     */
    public function getX(): int
    {
        return $this->x;
    }

    /**
     * Set X coordinate of the rover
     * @param int $x
     */
    public function setX(int $x): void
    {
        //check the Plateau boundaries
        if ($this->plateau->checkBoundariesAreExceeding($x, $this->getY())) {
            throw new InvalidInputException(
                "Mayday! Can't move the rover further. The X({$x}) boundary is exceeding the plateau borders."
            );
        }

        $this->x = $x;
    }

    /**
     * Get Y coordinate of the rover
     * @return int
     */
    public function getY(): int
    {
        return $this->y;
    }

    /**
     * Set Y coordinate of the rover
     * @param int $y
     */
    public function setY(int $y): void
    {
        //check the Plateau boundaries
        if ($this->plateau->checkBoundariesAreExceeding($this->getX(), $y)) {
            throw new InvalidInputException(
                "Mayday! Can't move the rover further. The Y({$y}) boundary is exceeding the plateau borders."
            );
        }

        $this->y = $y;
    }

    /**
     * Get orientation of the rover
     * @return string
     */
    public function getOrientation(): string
    {
        return $this->orientation;
    }

    /**
     * Set orientation of the rover
     * @param string $orientation
     */
    public function setOrientation(string $orientation): void
    {
        //validation
        $orientation = strtoupper($orientation);
        if(!in_array($orientation, array_keys(OrientationsConst::ORIENTATIONS_CONFIG))){
            throw new InvalidInputException("Invalid orientation: {$orientation}");
        }
        
        $this->orientation = $orientation;
    }
    
    /**
     * Get current position as string
     * @return string
     */
    public function getPosition(): string
    {
        return sprintf('%s %s %s', $this->getX(), $this->getY(), $this->getOrientation());
    }

    /**
     * Navigate the rover according to the commands given
     */
    public function navigate(): void
    {
        foreach ($this->getCommands() as $command) {
            /*
             * TODO:Separate this extensible behaviour to another set of RoverControl factory classes
             */
            switch ($command) {
                case CommandConst::RIGHT:
                    $this->turnRight();
                    break;
                case CommandConst::LEFT:
                    $this->turnLeft();
                    break;
                case CommandConst::MOVE:
                    $this->move();
                    break;
            }
        }
    }

    /**
     * Turn left the rover
     */
    protected function turnLeft(): void
    {
        $this->orientation = OrientationsConst::ORIENTATIONS_CONFIG[$this->orientation][CommandConst::LEFT];
    }

    /**
     * Turn right the rover
     */
    protected function turnRight(): void
    {
        $this->orientation = OrientationsConst::ORIENTATIONS_CONFIG[$this->orientation][CommandConst::RIGHT];
    }

    /**
     * Move the rover
     */
    protected function move(): void
    {
        $config = OrientationsConst::ORIENTATIONS_CONFIG[$this->orientation];
        if ($config['axis'] === OrientationsConst::X_AXIS) {
            $this->setX($this->x + $config[CommandConst::MOVE]);
        } else {
            $this->setY($this->y + $config[CommandConst::MOVE]);
        }
    }
}