<?php

namespace Application\Provider;

use League\Container\ServiceProvider\{AbstractServiceProvider, BootableServiceProviderInterface};
use Borsch\Config\Config;
use DateTimeZone;
use Monolog\Handler\StreamHandler;
use Monolog\{Formatter\JsonFormatter, Level, Logger};
use Monolog\Processor\PsrLogMessageProcessor;
use Psr\Log\{LoggerAwareInterface, LoggerInterface};

class LoggingServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{

    public function boot(): void
    {
        $this
            ->getContainer()
            ->inflector(
                LoggerAwareInterface::class,
                fn(LoggerAwareInterface $class) => $class->setLogger(
                    $this->getContainer()->get(Logger::class)->withName(get_class($class))
                ));
    }

    public function provides(string $id): bool
    {
        return in_array($id, [
            LoggerInterface::class,
            Logger::class
        ]);
    }

    public function register(): void
    {
        $this
            ->getContainer()
            ->add(LoggerInterface::class, fn(Config $config): LoggerInterface => new Logger(
                $config->getOrDefault('APP_NAME', 'app'),
                [
                    (new StreamHandler(
                        'php://stderr',
                        Level::fromName($config->getOrDefault('LOG_LEVEL', 'Debug'))
                    ))->setFormatter(new JsonFormatter())
                ],
                [new PsrLogMessageProcessor(removeUsedContextFields: true)],
                new DateTimeZone($config->getOrDefault('TIMEZONE', 'UTC'))
            ))->addArgument($this->getContainer()->get(Config::class));

        $this
            ->getContainer()
            ->add(Logger::class, fn(): Logger => $this->getContainer()->get(LoggerInterface::class));
    }
}
