<?php

namespace Application\Provider;

use Borsch\Config\Config;
use FastRoute\Dispatcher;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Middlewares\ErrorFormatter\{HtmlFormatter,
    JsonFormatter,
    PlainFormatter};
use Middlewares\{Cors, ErrorHandler};
use Neomerx\Cors\Analyzer;
use Neomerx\Cors\Strategies\Settings;
use Psr\Http\Message\ResponseFactoryInterface;
use Router\{FastRouteDispatcher, FastRouteRouter, ImplicitHead, ImplicitOption, MethodNotAllowed, NotFound};
use function FastRoute\cachedDispatcher;
use function FastRoute\simpleDispatcher;

class MiddlewareServiceProvider extends AbstractServiceProvider
{

    public function provides(string $id): bool
    {
        return in_array($id, [
            Cors::class,
            ErrorHandler::class,
            Dispatcher::class,
            FastRouteRouter::class,
            FastRouteDispatcher::class,
            ImplicitHead::class,
            ImplicitOption::class,
            MethodNotAllowed::class,
            NotFound::class
        ]);
    }

    public function register(): void
    {
        $this
            ->getContainer()
            ->add(Cors::class, function (): Cors {
                $settings = new Settings();
                $settings->init('http', 'localhost', 80);
                $settings->setAllowedOrigins(['*']); // Adjust as needed for security in production
                $settings->setAllowedMethods(['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']);
                $settings->setAllowedHeaders(['Content-Type', 'Authorization', 'X-Request-Id']);
                $settings->setExposedHeaders(['X-Request-Id']);
                $settings->setCredentialsSupported();
                $settings->setPreFlightCacheMaxAge(3600);

                $analyzer = Analyzer::instance($settings);

                return new Cors(
                    $analyzer,
                    $this->getContainer()->get(ResponseFactoryInterface::class)
                );
            });

        $this
            ->getContainer()
            ->add(ErrorHandler::class, fn() => new ErrorHandler([
                new JsonFormatter(),
                new PlainFormatter(),
                new HtmlFormatter(),
            ]));

        $this
            ->getContainer()
            ->add(
                Dispatcher::class,
                function (Config $config): Dispatcher {
                    $callback = (require_once config_path('routes.php'));

                    return $config->getOrDefault('APP_ENV', 'development') == 'production'
                        ? cachedDispatcher($callback, [
                            'cacheFile' => cache_path('route.cache.php')
                        ])
                        : simpleDispatcher($callback);
                }
            )->addArgument($this->getContainer()->get(Config::class));

        $this
            ->getContainer()
            ->add(
                FastRouteRouter::class,
                fn(Dispatcher $dispatcher): FastRouteRouter => new FastRouteRouter($dispatcher)
            )->addArgument($this->getContainer()->get(Dispatcher::class));

        $this
            ->getContainer()
            ->add(
                FastRouteDispatcher::class,
                fn(): FastRouteDispatcher => (new FastRouteDispatcher($this->getContainer()))
            );

        $this
            ->getContainer()
            ->add(
                ImplicitHead::class,
                fn(): ImplicitHead => new ImplicitHead(
                    $this->getContainer()->get(Dispatcher::class),
                    $this->getContainer()->get(ResponseFactoryInterface::class)
                )
            );

        $this
            ->getContainer()
            ->add(
                ImplicitOption::class,
                fn(): ImplicitOption => new ImplicitOption(
                    $this->getContainer()->get(ResponseFactoryInterface::class)
                )
            );

        $this
            ->getContainer()
            ->add(
                MethodNotAllowed::class,
                fn(): MethodNotAllowed => new MethodNotAllowed(
                    $this->getContainer()->get(ResponseFactoryInterface::class)
                )
            );

        $this
            ->getContainer()
            ->add(
                NotFound::class,
                fn(): NotFound => new NotFound(
                    $this->getContainer()->get(ResponseFactoryInterface::class)->createResponse(404)
                )
            );
    }
}
