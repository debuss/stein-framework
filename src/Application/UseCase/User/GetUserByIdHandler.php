<?php

namespace Application\UseCase\User;

use Domain\User\{User, UserRepositoryInterface};

readonly class GetUserByIdHandler
{

    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    public function handle(GetUserByIdCommand $command): User
    {
        return $this->repository->findById($command->id);
    }
}
