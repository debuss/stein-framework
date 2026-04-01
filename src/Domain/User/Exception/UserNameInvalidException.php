<?php

namespace Domain\User\Exception;

use InvalidArgumentException;

class UserNameInvalidException extends InvalidArgumentException
{

    public static function create() : self
    {
        return new self("A user with an empty name is not possible.", 422);
    }
}
