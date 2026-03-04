<?php

use Borsch\RequestHandler\RequestHandlerInterface;
use Middlewares\{Cors, ErrorHandler, JsonPayload, TrailingSlash};
use ProblemDetails\ProblemDetailsMiddleware;
use Psr\Container\ContainerInterface;
use Router\{FastRouteDispatcher, FastRouteRouter, ImplicitHead, ImplicitOption, MethodNotAllowed, NotFound};

return static function (RequestHandlerInterface $handler, ContainerInterface $container): void {

    $handler->middlewares([
        $container->get(Cors::class),
        $container->get(ErrorHandler::class),
        $container->get(ProblemDetailsMiddleware::class),
        $container->get(TrailingSlash::class),
        $container->get(FastRouteRouter::class),
        $container->get(ImplicitHead::class),
        $container->get(ImplicitOption::class),
        $container->get(MethodNotAllowed::class),
        $container->get(JsonPayload::class),
        $container->get(FastRouteDispatcher::class),
        $container->get(NotFound::class)
    ]);

};
