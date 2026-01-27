<?php

namespace Domain\User\ValueObject;

use Domain\User\Exception\UserIdInvalidException;
use JsonSerializable;

readonly class UserId implements JsonSerializable
{

    /**
     * @throws UserIdInvalidException
     */
    public function __construct(
        public ?int $id
    ) {
        if ($id !== null && $id <= 0) {
            throw UserIdInvalidException::create($id);
        }
    }

    public function __toString(): string
    {
        return (string)$this->id;
    }

    public function jsonSerialize(): int
    {
        return $this->id;
    }
}
