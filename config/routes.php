<?php

use Application\Controller\{HomePageController, UserController};
use Middlewares\JsonPayload;
use League\Route\{RouteGroup, RouterInterface};
use Psr\Container\ContainerInterface;

return static function (RouterInterface $router, ContainerInterface $container): void {

    $router->map('GET', '/', HomePageController::class);

    $router->group('/api/v1', function (RouteGroup $group) {

        $group->map('GET', '/users[/{id:\d+}]', UserController::class);

    })->lazyMiddlewares([JsonPayload::class]);

};
