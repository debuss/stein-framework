<?php

namespace Application\Controller;

use Application\UseCase\User\{GetAllUsersCommand, GetAllUsersHandler, GetUserByIdCommand, GetUserByIdHandler};
use JsonException;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Router\Attribute\{Get, Group};

#[Group('/api/v1')]
class UserController extends Controller
{

    public function __construct(
        protected GetAllUsersHandler $handlerAll,
        protected GetUserByIdHandler $handlerById
    ) {}

    /**
     * @throws JsonException
     */
    #[Get('/users[/{id:\d+}]')]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute('id');

        $users = $id !== null
            ? $this->handlerById->handle(new GetUserByIdCommand((int)$id))
            : $this->handlerAll->handle(new GetAllUsersCommand());

        return $this->response()->json($users);
    }
}
