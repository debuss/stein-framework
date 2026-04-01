<?php

use Application\Controller\RoutingErrorController;
use Borsch\Config\Config;
use League\Container\Container;
use League\Container\Event\ServiceResolvedEvent;
use League\Route\{MatchResult, MatchStatus, RouterInterface};
use Psr\Http\Message\ServerRequestInterface;

require_once __DIR__ . '/../vendor/autoload.php';

/** @var Container $container */
$container = require __DIR__ . '/../config/container.php';

$router = $container->get(RouterInterface::class);
$error_controller = $container->get(RoutingErrorController::class);

// Refresh the container ServerRequestInterface binding with the current request
$server_request = null;
$container->listen(ServiceResolvedEvent::class, function (ServiceResolvedEvent $event) use (&$server_request) {
    if ($server_request !== null) {
        $event->setResolved($server_request);
    }
})->forId(ServerRequestInterface::class);

$handler_callback = static function () use (&$server_request, $router, $error_controller) {
    $server_request = frankenphp_psr7_incoming_request();

    if (str_starts_with($server_request->getUri()->getPath(), '/api/')) {
        // Force the Accept header if an API route is called so that exceptions are displayed as JSON instead of HTML
        // in the ErrorHandler middleware
        $server_request = $server_request->withHeader('Accept', 'application/json');
    }

    $result = $router->match($server_request);

    $server_request = $server_request->withAttribute(MatchResult::class, $result);

    $response = match($result->getStatus()) {
        MatchStatus::Found => $router->dispatch($server_request),
        default => $error_controller->handle($server_request)
    };

    emit($response);
};

// Start the process loop
$max_nb_request = $container->get(Config::class)->getOrDefault(
    'FRANKENPHP_NB_REQUEST_TO_RESTART',
    1000
);

for ($nb_requests = 0, $running = true; $running; ++$nb_requests) {
    $running = frankenphp_handle_request($handler_callback);

    // Optional : Force worker restart to avoid memory leaks
    if ($nb_requests > $max_nb_request) {
        break;
    }
}
