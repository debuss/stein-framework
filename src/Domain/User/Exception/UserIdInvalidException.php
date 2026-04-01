<?php

namespace Domain\User\Exception;

use InvalidArgumentException;

class UserIdInvalidException extends InvalidArgumentException
{

    public static function create(int $id) : self
    {
        return new self("A user with an ID $id is not possible.", 422);
    }
}
