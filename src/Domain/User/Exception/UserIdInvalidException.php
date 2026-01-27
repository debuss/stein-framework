<?php

namespace Domain\User\Exception;

use ProblemDetails\{ProblemDetails, ProblemDetailsException};

class UserIdInvalidException extends ProblemDetailsException
{

    public static function create(int $id) : self
    {
        $problem_details = new ProblemDetails(
            'error://user-id-invalid',
            'User ID must be a positive integer or null.',
            401,
            "A user with an ID $id is not possible."
        );

        return new self($problem_details);
    }
}
