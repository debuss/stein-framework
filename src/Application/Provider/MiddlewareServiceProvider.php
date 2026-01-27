<?php

namespace Application\Provider;

use Borsch\Config\Config;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Middlewares\ErrorFormatter\{HtmlFormatter,
    JsonFormatter,
    PlainFormatter};
use Middlewares\{Cors, ErrorHandler, FastRoute, RequestHandler};
use Neomerx\Cors\Analyzer;
use Neomerx\Cors\Strategies\Settings;
use Psr\Http\Message\ResponseFactoryInterface;
use function FastRoute\cachedDispatcher;
use function FastRoute\simpleDispatcher;

class MiddlewareServiceProvider extends AbstractServiceProvider
{

    public function provides(string $id): bool
    {
        return in_array($id, [
            Cors::class,
            ErrorHandler::class,
            FastRoute::class,
            RequestHandler::class
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
                FastRoute::class,
                function (Config $config): FastRoute {
                    $callback = (require_once config_path('routes.php'));

                    $dispatcher = $config->getOrDefault('APP_ENV', 'development') == 'production'
                        ? cachedDispatcher($callback, [
                            'cacheFile' => cache_path('route.cache.php')
                        ])
                        : simpleDispatcher($callback);

                    return new FastRoute(
                        $dispatcher,
                        $this->getContainer()->get(ResponseFactoryInterface::class)
                    );
                }
            )->addArgument($this->getContainer()->get(Config::class));

        $this
            ->getContainer()
            ->add(
                RequestHandler::class,
                fn(): RequestHandler => (new RequestHandler($this->getContainer()))
            );
    }
}
