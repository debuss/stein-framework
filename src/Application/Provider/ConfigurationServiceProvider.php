<?php

namespace Application\Provider;

use Borsch\Config\{Aggregator, Config};
use Borsch\Config\Reader\Ini;
use League\Container\ServiceProvider\AbstractServiceProvider;

class ConfigurationServiceProvider extends AbstractServiceProvider
{

    public function provides(string $id): bool
    {
        return $id === Config::class;
    }

    public function register(): void
    {
        $this
            ->getContainer()
            ->add(Config::class, function (): Config {
                $aggregator = new Aggregator(
                    [
                        $_ENV, // Placed first so ENV from server can be overwritten by configuration files
                        (new Ini())->fromFile(app_path('.env'))
                    ],
                    cache_path('config.cache.php'),
                    str_starts_with($_ENV['APP_ENV'] ?? 'development', 'prod')
                );

                return $aggregator->getMergedConfig();
            });
    }
}
