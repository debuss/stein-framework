<?php

namespace Application\Provider;

use Borsch\Config\Config;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\Cache\{FileCache, Router as CachedRouter};
use League\Route\{Router, RouterInterface};
use League\Route\Strategy\ApplicationStrategy;

class RoutingServiceProvider extends AbstractServiceProvider
{

    public function provides(string $id): bool
    {
        return $id === RouterInterface::class;
    }

    public function register(): void
    {
        $this
            ->getContainer()
            ->add(RouterInterface::class, fn(Config $config) => new CachedRouter(
                function (Router $router): Router {
                    $strategy = new ApplicationStrategy();
                    $strategy->setContainer($this->getContainer());
                    $router->setStrategy($strategy);

                    (require config_path('middlewares.php'))($router, $this->getContainer());
                    (require config_path('routes.php'))($router, $this->getContainer());

                    return $router;
                },
                new FileCache(cache_path('route.cache.php'), 86400),
                str_starts_with($config->getOrDefault('APP_ENV', 'development'), 'prod')
            ))->addArgument($this->getContainer()->get(Config::class));
    }
}
