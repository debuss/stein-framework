<?php

use League\Route\RouterInterface;
use Psr\Container\ContainerInterface;
use Routing\AttributeRouteLoader;

return static function (RouterInterface $router, ContainerInterface $container): void {

    $loader = new AttributeRouteLoader(
        'Application\\Controller',
        __DIR__ . '/../src/Application/Controller'
    );

    $routes = $loader->getRouteDefinitions();
    foreach ($routes as $route) {
        $router->map($route->methods, $route->path, $route->handler[0]);
    }

    // Still possible to define route manually, example :
    //   $router->map('GET', '/my-path', MyPathController::class);

};
