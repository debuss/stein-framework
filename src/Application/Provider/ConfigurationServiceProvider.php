<?php

namespace Application\Provider;

use Borsch\Config\Aggregator;
use Borsch\Config\Config;
use Borsch\Config\Reader\Ini;
use League\Container\ServiceProvider\AbstractServiceProvider;

class ConfigurationServiceProvider extends AbstractServiceProvider
{

    public function provides(string $id): bool
    {
        return $id == Config::class;
    }

    public function register(): void
    {
        $this
            ->getContainer()
            ->add(Config::class, function (): Config {
                $aggregator = new Aggregator([
                    (new Ini())->fromFile(app_path('.env'))
                ]);

                return $aggregator->getMergedConfig();
            });
    }
}
