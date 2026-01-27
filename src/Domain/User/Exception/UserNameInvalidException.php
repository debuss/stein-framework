<?php

namespace Domain\User\Exception;

use ProblemDetails\{ProblemDetails, ProblemDetailsException};

class UserNameInvalidException extends ProblemDetailsException
{

    public static function create() : self
    {
        $problem_details = new ProblemDetails(
            'error://user-name-invalid',
            'User name cannot be empty.',
            401,
            "A user with an empty name is not possible."
        );

        return new self($problem_details);
    }
}
