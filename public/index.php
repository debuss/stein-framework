<?php

use Borsch\RequestHandler\EmitterInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

require_once __DIR__ . '/../vendor/autoload.php';

(function() {
    /** @var ContainerInterface $container */
    $container = require __DIR__ . '/../config/container.php';

    $server_request = $container->get(ServerRequestInterface::class);

    $handler = $container->get(RequestHandlerInterface::class);

    (require_once __DIR__ . '/../config/middlewares.php')($handler, $container);

    $container->get(EmitterInterface::class)->emit(
        $handler->handle($server_request)
    );
})();
