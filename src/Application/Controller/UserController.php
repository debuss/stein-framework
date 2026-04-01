<?php

namespace Application\Controller;

use Application\UseCase\User\{GetAllUsersCommand, GetAllUsersHandler, GetUserByIdCommand, GetUserByIdHandler};
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};

class UserController extends Controller
{

    public function __construct(
        protected GetAllUsersHandler $handlerAll,
        protected GetUserByIdHandler $handlerById
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute('id');

        $users = $id !== null
            ? $this->handlerById->handle(new GetUserByIdCommand((int)$id))
            : $this->handlerAll->handle(new GetAllUsersCommand());

        return new JsonResponse($users);
    }
}
