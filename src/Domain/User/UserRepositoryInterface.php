<?php

namespace Domain\User;

interface UserRepositoryInterface
{

    /**
     * @return User[]
     */
    public function findAll(): array;

    public function findById(int $id): User;
}
