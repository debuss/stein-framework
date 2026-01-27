<?php

namespace Application\Provider;

use Awareness\ResponseFactoryAwareInterface;
use Awareness\StreamFactoryAwareInterface;
use Borsch\RequestHandler\{Emitter, EmitterInterface, RequestHandler};
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\{RequestFactoryInterface,
    ResponseFactoryInterface,
    ServerRequestFactoryInterface,
    ServerRequestInterface,
    StreamFactoryInterface,
    UploadedFileFactoryInterface,
    UriFactoryInterface};

class HttpServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{

    public function boot(): void
    {
        $this
            ->getContainer()
            ->inflector(
                ResponseFactoryAwareInterface::class,
                fn (ResponseFactoryAwareInterface $class) => $class->setResponseFactory(
                    $this->getContainer()->get(ResponseFactoryInterface::class)
                ));

        $this
            ->getContainer()
            ->inflector(
                StreamFactoryAwareInterface::class,
                fn (StreamFactoryAwareInterface $class) => $class->setStreamFactory(
                $this->getContainer()->get(StreamFactoryInterface::class)
            ));
    }

    public function provides(string $id): bool
    {
        return in_array($id, [
            ServerRequestFactoryInterface::class,
            ResponseFactoryInterface::class,
            RequestFactoryInterface::class,
            StreamFactoryInterface::class,
            UploadedFileFactoryInterface::class,
            UriFactoryInterface::class,
            ServerRequestInterface::class,
            RequestHandlerInterface::class,
            EmitterInterface::class
        ]);
    }

    public function register(): void
    {
        $this
            ->getContainer()
            ->add(ServerRequestFactoryInterface::class, Psr17Factory::class);

        $this
            ->getContainer()
            ->add(ResponseFactoryInterface::class, Psr17Factory::class);

        $this
            ->getContainer()
            ->add(RequestFactoryInterface::class, Psr17Factory::class);

        $this
            ->getContainer()
            ->add(StreamFactoryInterface::class, Psr17Factory::class);

        $this
            ->getContainer()
            ->add(UploadedFileFactoryInterface::class, Psr17Factory::class);

        $this
            ->getContainer()
            ->add(UriFactoryInterface::class, Psr17Factory::class);

        $this
            ->getContainer()
            ->add(ServerRequestInterface::class, fn ($factory): ServerRequestInterface =>
                (new ServerRequestCreator($factory, $factory, $factory, $factory))
                    ->fromGlobals()
            )
            ->addArgument(new Psr17Factory());

        $this
            ->getContainer()
            ->add(RequestHandlerInterface::class, RequestHandler::class);

        $this
            ->getContainer()
            ->add(EmitterInterface::class, Emitter::class);
    }
}
