<?php

namespace Application\UseCase\User;

use Domain\User\{User, UserRepositoryInterface};

readonly class GetAllUsersHandler
{

    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    /**
     * @return User[]
     */
    public function handle(GetAllUsersCommand $command): array
    {
        return $this->repository->findAll();
    }
}
