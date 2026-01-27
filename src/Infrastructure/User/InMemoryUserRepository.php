<?php

namespace Infrastructure\User;

use Domain\User\Exception\{UserIdInvalidException, UserNameInvalidException, UserNotFoundException};
use Domain\User\{User, UserRepositoryInterface};
use Psr\Log\{LoggerAwareInterface, LoggerAwareTrait, LoggerInterface};

class InMemoryUserRepository implements UserRepositoryInterface, LoggerAwareInterface
{

    use LoggerAwareTrait;

    /** @var User[] */
    private array $users;

    /**
     * @throws UserNameInvalidException
     * @throws UserIdInvalidException
     */
    public function __construct()
    {
        $this->users = [
            1 => new User(1, 'Victor', 'Frankenstein'),
            2 => new User(2, 'Elizabeth', 'Lavenza'),
            3 => new User(3, 'Robert', 'Walton'),
            4 => new User(4, 'Henry', 'Clerval'),
            5 => new User(5, 'Justine', 'Moritz')
        ];
    }

    public function findAll(): array
    {
        $this->logger->info('Retrieving all users from in-memory repository.');

        return array_values($this->users);
    }

    /**
     * @throws UserNotFoundException
     */
    public function findById(int $id): User
    {
        if (!isset($this->users[$id])) {
            $this->logger->error("User with ID $id not found in repository.");

            throw UserNotFoundException::create($id);
        }

        $this->logger->info("User with ID $id retrieved from repository.");

        return $this->users[$id];
    }
}
