<?php

use FastRoute\Attribute\AttributeRouteLoader;
use FastRoute\RouteCollector;

return static function (RouteCollector $collector): void {

    $loader = new AttributeRouteLoader(
        'Application\\Controller\\',
        source_path('Application/Controller')
    );

    $loader->load($collector);

};
