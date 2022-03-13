<?php

namespace App\Models;

class Plateau
{
    /**
     * @var int[] 
     */
    protected array $lowerLeft = [0, 0];
    
    public function __construct(protected array $upperRight)
    {
    }

    /**
     * Get maximum coordinate
     * @return array
     */
    public function getUpperRight(): array
    {
        return $this->upperRight;
    }

    /**
     * Get minimum coordinate
     * @return array
     */
    public function getLowerLeft(): array
    {
        return $this->lowerLeft;
    }

    /**
     * Check the given coordinates are exceeding the boundaries of the Plateau
     * @param int $x
     * @param int $y
     * @return bool
     */
    public function checkBoundariesAreExceeding(int $x, int $y): bool
    {
        list($minX, $minY) = $this->getLowerLeft();
        list($maxX, $maxY) = $this->getUpperRight();

        return ($x > $maxX || $x < $minX || $y > $maxY || $y < $minY);
    }

}