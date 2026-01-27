<?php

namespace Application\Controller;

use Application\Routing\Attribute\Route;
use Application\UseCase\User\{GetAllUsersCommand, GetAllUsersHandler, GetUserByIdCommand, GetUserByIdHandler};
use JsonException;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};

class UserController extends Controller
{

    public function __construct(
        protected GetAllUsersHandler $handlerAll,
        protected GetUserByIdHandler $handlerById
    ) {}

    /**
     * @throws JsonException
     */
    #[Route('/api/v1/users[/{id:\d+}]', methods: ['GET'])]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute('id');

        $users = $id !== null
            ? $this->handlerById->handle(new GetUserByIdCommand((int)$id))
            : $this->handlerAll->handle(new GetAllUsersCommand());

        return $this->response()->json($users);
    }
}
