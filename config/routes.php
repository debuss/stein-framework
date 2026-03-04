<?php

use FastRoute\RouteCollector;
use Router\AttributeRouteLoader;

return static function (RouteCollector $collector): void {

    $loader = new AttributeRouteLoader(
        'Application\\Controller\\',
        source_path('Application/Controller')
    );

    $loader->load($collector);

};
