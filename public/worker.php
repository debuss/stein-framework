<?php

use Borsch\RequestHandler\EmitterInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

require_once __DIR__ . '/../vendor/autoload.php';

/** @var ContainerInterface $container */
$container = require __DIR__ . '/../config/container.php';

$handler = $container->get(RequestHandlerInterface::class);

(require_once __DIR__ . '/../config/middlewares.php')($handler, $container);

$emitter = $container->get(EmitterInterface::class);

$handler_callback = static function () use ($handler, $emitter) {
    $response = $handler->handle(
        frankenphp_psr7_incoming_request()
    );

    $emitter->emit($response);
};

// On lance la boucle de gestion
for ($nb_requests = 0, $running = true; $running; ++$nb_requests) {
    $running = frankenphp_handle_request($handler_callback);

    // Optional : Force worker restart to avoid memory leaks
    if ($nb_requests > 1000) {
        break;
    }
}
