<?php

use Application\Controller\RoutingErrorController;
use League\Route\{MatchResult, MatchStatus, RouterInterface};
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

require_once __DIR__ . '/../vendor/autoload.php';

(function() {
    /** @var ContainerInterface $container */
    $container = require config_path('container.php');

    $server_request = $container->get(ServerRequestInterface::class);
    if (str_starts_with($server_request->getUri()->getPath(), '/api/')) {
        // Force the Accept header if an API route is called so that exceptions are displayed as JSON instead of HTML
        // in the ErrorHandler middleware
        $server_request = $server_request->withHeader('Accept', 'application/json');
    }

    $router = $container->get(RouterInterface::class);

    $result = $router->match($server_request);

    $server_request = $server_request->withAttribute(MatchResult::class, $result);

    $response = match($result->getStatus()) {
        MatchStatus::Found => $router->dispatch($server_request),
        default => $container->get(RoutingErrorController::class)->handle($server_request)
    };

    emit($response);
})();
