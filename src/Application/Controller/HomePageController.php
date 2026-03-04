<?php

namespace Application\Controller;

use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Router\Attribute\Get;

class HomePageController extends Controller
{

    #[Get('/')]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->logger->info('Displaying home page');

        return $this->response()->view('home');
    }
}
