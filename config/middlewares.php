<?php

use Middlewares\{ErrorHandler, JsonPayload};
use League\Route\{Router, RouterInterface};
use Psr\Container\ContainerInterface;

/**
 * Define middlewares that must be run on the router for every matched route.
 *
 * @see https://route.thephpleague.com/unstable/middleware
 */
return static function (RouterInterface $router, ContainerInterface $container): void {

    /** @var Router $router */
    $router->lazyMiddlewares([
        ErrorHandler::class,
        JsonPayload::class
    ]);

};
