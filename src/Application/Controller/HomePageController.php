<?php

namespace Application\Controller;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Routing\Attribute\{BaseController, HttpGet};

#[BaseController]
class HomePageController extends Controller
{

    #[HttpGet('/')]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->logger->info('Displaying home page');

        return new HtmlResponse($this->templateRenderer->render('home'));
    }
}
