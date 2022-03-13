<?php

namespace App\Constants;

class OrientationsConst {
    const NORTH = 'N';
    const SOUTH = 'S';
    const EAST = 'E';
    const WEST = 'W';
    
    const X_AXIS = 'X';
    const Y_AXIS = 'Y';

    public const ORIENTATIONS_CONFIG = [
        self::NORTH => [
            'R' => self::EAST,
            'L' => self::WEST,
            'axis' => self::Y_AXIS,
            'M' => 1,
        ],
        self::SOUTH => [
            'R' => self::WEST,
            'L' => self::EAST,
            'axis' => self::Y_AXIS,
            'M' => -1,
        ],
        self::EAST => [
            'R' => self::SOUTH,
            'L' => self::NORTH,
            'axis' => self::X_AXIS,
            'M' => 1,
        ],
        self::WEST => [
            'R' => self::NORTH,
            'L' => self::SOUTH,
            'axis' => self::X_AXIS,
            'M' => -1,
        ],
    ];
}