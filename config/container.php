<?php

use Application\Provider\{ConfigurationServiceProvider,
    HttpServiceProvider,
    LoggingServiceProvider,
    MiddlewareServiceProvider,
    ViewServiceProvider};
use League\Container\{Container, ReflectionContainer};
use Domain\User\UserRepositoryInterface;
use Infrastructure\User\InMemoryUserRepository;
use Psr\Log\LoggerInterface;

$container = (new Container());
$container->defaultToShared();
$container->delegate(new ReflectionContainer(true));

$container->addServiceProvider(new ConfigurationServiceProvider());
$container->addServiceProvider(new HttpServiceProvider());
$container->addServiceProvider(new MiddlewareServiceProvider());
$container->addServiceProvider(new LoggingServiceProvider());
$container->addServiceProvider(new ViewServiceProvider());

// Add more service providers or definition as needed

$container->add(
    UserRepositoryInterface::class,
    fn ($logger): UserRepositoryInterface => new InMemoryUserRepository($logger)
)->addArgument($container->get(LoggerInterface::class));

return $container;
