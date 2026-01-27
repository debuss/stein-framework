<?php

namespace Application\UseCase\User;

readonly class GetUserByIdCommand
{

    public function __construct(
        public int $id
    ) {}
}
