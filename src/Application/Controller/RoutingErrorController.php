<?php

namespace Application\Controller;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\HtmlResponse;
use League\Route\{MatchResult, MatchStatus};
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};

class RoutingErrorController implements RequestHandlerInterface
{

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return match ($request->getAttribute(MatchResult::class)->getStatus()) {
            MatchStatus::NotFound => $this->getNotFoundResponse(),
            MatchStatus::MethodNotAllowed => $this->getMethodNotAllowedResponse($request),
            default => $this->getConditionNotMetResponse($request)
        };
    }

    private function getNotFoundResponse(): ResponseInterface
    {
        return new HtmlResponse(
            '<h1>404 Not Found</h1>',
            404
        );
    }

    private function getMethodNotAllowedResponse(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(
            status: 405,
            headers: [
                'Allow' => $request->getAttribute(MatchResult::class)->getAllowedMethods()
            ]
        );
    }

    private function getConditionNotMetResponse(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(
            status: 412,
            headers: [
                'X-Expected-Host' => $request->getAttribute(MatchResult::class)->getRoute()?->getHost(),
                'X-Expected-Scheme' => $request->getAttribute(MatchResult::class)->getRoute()?->getScheme(),
                'X-Expected-Port' => $request->getAttribute(MatchResult::class)->getRoute()?->getPort()
            ]
        );
    }
}
