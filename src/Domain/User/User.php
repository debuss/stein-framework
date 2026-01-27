<?php

namespace Domain\User;

use Domain\User\Exception\{UserIdInvalidException, UserNameInvalidException};
use Domain\User\ValueObject\{UserId, UserName};
use JsonSerializable;

readonly class User implements JsonSerializable
{

    public UserId $id;
    public UserName $firstname;
    public UserName $lastname;

    /**
     * @throws UserNameInvalidException
     * @throws UserIdInvalidException
     */
    public function __construct(
        ?int $id,
        string $firstName,
        string $lastName
    ) {
        $this->id = new UserId($id);
        $this->firstname = new UserName($firstName);
        $this->lastname = new UserName($lastName);
    }

    /**
     * @return array<string, int|string>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname
        ];
    }
}
