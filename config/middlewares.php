<?php

use Borsch\RequestHandler\RequestHandlerInterface;
use Middlewares\{Cors, ErrorHandler, FastRoute, JsonPayload, RequestHandler as Dispatch, TrailingSlash};
use ProblemDetails\ProblemDetailsMiddleware;
use Psr\Container\ContainerInterface;

return static function (RequestHandlerInterface $handler, ContainerInterface $container): void {

    $handler->middlewares([
        $container->get(Cors::class),
        $container->get(ErrorHandler::class),
        $container->get(ProblemDetailsMiddleware::class),
        $container->get(TrailingSlash::class),
        $container->get(FastRoute::class),
        $container->get(JsonPayload::class),
        $container->get(Dispatch::class)
    ]);

};
