<?php

namespace Application\Controller;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};

class HomePageController extends Controller
{

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->logger->info('Displaying home page');

        return new HtmlResponse($this->templateRenderer->render('home'));
    }
}
