<?php

namespace Application\Provider;

use League\Container\Container;
use Awareness\{ResponseFactoryAwareInterface, StreamFactoryAwareInterface};
use Laminas\Diactoros\{RequestFactory,
    ResponseFactory,
    ServerRequestFactory,
    StreamFactory,
    UploadedFileFactory,
    UriFactory};
use League\Container\ServiceProvider\{AbstractServiceProvider, BootableServiceProviderInterface};
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
        /** @var Container $container */
        $container = $this->getContainer();

        $container->afterResolve(
            ResponseFactoryAwareInterface::class,
            fn (ResponseFactoryAwareInterface $class) => $class->setResponseFactory(
                $this->getContainer()->get(ResponseFactoryInterface::class)
            )
        );

        $container->afterResolve(
            StreamFactoryAwareInterface::class,
            fn (StreamFactoryAwareInterface $class) => $class->setStreamFactory(
                $this->getContainer()->get(StreamFactoryInterface::class)
            )
        );
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
            ServerRequestInterface::class
        ]);
    }

    public function register(): void
    {
        $this
            ->getContainer()
            ->add(ServerRequestFactoryInterface::class, ServerRequestFactory::class);

        $this
            ->getContainer()
            ->add(ResponseFactoryInterface::class, ResponseFactory::class);

        $this
            ->getContainer()
            ->add(RequestFactoryInterface::class, RequestFactory::class);

        $this
            ->getContainer()
            ->add(StreamFactoryInterface::class, StreamFactory::class);

        $this
            ->getContainer()
            ->add(UploadedFileFactoryInterface::class, UploadedFileFactory::class);

        $this
            ->getContainer()
            ->add(UriFactoryInterface::class, UriFactory::class);

        $this
            ->getContainer()
            ->add(ServerRequestInterface::class, function (): ServerRequestInterface {
                $server_request = ServerRequestFactory::fromGlobals(
                    $_SERVER,
                    $_GET,
                    $_POST,
                    $_COOKIE,
                    $_FILES
                );

                // Remove trailing slashes and spaces (without redirection)
                $uri = $server_request->getUri();
                if ($uri->getPath() !== '/') {
                    $uri = $uri->withPath(rtrim($uri->getPath(), '/ '));
                }

                return $server_request->withUri($uri);
            });
    }
}
