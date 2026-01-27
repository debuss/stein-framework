<?php

namespace Domain\User\ValueObject;

use Domain\User\Exception\UserNameInvalidException;
use JsonSerializable;

readonly class UserName implements JsonSerializable
{

    /**
     * @throws UserNameInvalidException
     */
    public function __construct(
        public string $name
    ) {
        if (trim($this->name) === '') {
            throw UserNameInvalidException::create();
        }
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function jsonSerialize(): mixed
    {
        return $this->name;
    }
}
