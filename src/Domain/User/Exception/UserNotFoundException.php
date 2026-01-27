<?php

namespace Domain\User\Exception;

use ProblemDetails\{ProblemDetails, ProblemDetailsException};

class UserNotFoundException extends ProblemDetailsException
{

    public static function create(int $id) : self
    {
        $problem_details = new ProblemDetails(
            'error://user-not-found',
            'The requested user does not exist.',
            404,
            "The user with ID $id does not exist."
        );

        return new self($problem_details);
    }
}
