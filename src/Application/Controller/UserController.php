<?php

namespace Application\Controller;

use Application\UseCase\User\{GetAllUsersCommand, GetAllUsersHandler, GetUserByIdCommand, GetUserByIdHandler};
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Routing\Attribute\ApiController;
use Routing\Attribute\HttpGet;

#[ApiController('/api/v1')]
class UserController extends Controller
{

    public function __construct(
        protected GetAllUsersHandler $handlerAll,
        protected GetUserByIdHandler $handlerById
    ) {}

    #[HttpGet('/users[/{id:\d+}]')]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute('id');

        $users = $id !== null
            ? $this->handlerById->handle(new GetUserByIdCommand((int)$id))
            : $this->handlerAll->handle(new GetAllUsersCommand());

        return new JsonResponse($users);
    }
}
