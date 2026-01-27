<?php

namespace Application\Controller;

use Application\Routing\Attribute\Route;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};

class HomePageController extends Controller
{

    #[Route('/', methods: ['GET'])]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->logger->info('Displaying home page');

        return $this->response()->view('home');
    }
}
