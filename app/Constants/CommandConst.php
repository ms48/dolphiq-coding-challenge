<?php

namespace App\Constants;

class CommandConst {
    const MOVE = 'M';
    const RIGHT = 'R';
    const LEFT = 'L';
    
    const VALID_COMMANDS = [self::MOVE, self::RIGHT, self::LEFT];
}