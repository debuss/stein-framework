<?php

namespace Domain\User\Exception;

use RuntimeException;

class UserNotFoundException extends RuntimeException
{

    public static function create(int $id) : self
    {
        return new self("The user with ID $id does not exist.", 404);
    }
}
