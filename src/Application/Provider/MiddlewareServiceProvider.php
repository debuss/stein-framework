<?php

namespace Application\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Middlewares\ErrorFormatter\{HtmlFormatter, JsonFormatter};
use Middlewares\ErrorHandler;

class MiddlewareServiceProvider extends AbstractServiceProvider
{

    public function provides(string $id): bool
    {
        return in_array($id, [
            ErrorHandler::class,
        ]);
    }

    public function register(): void
    {
        $this
            ->getContainer()
            ->add(ErrorHandler::class, fn() => new ErrorHandler([
                new HtmlFormatter(),
                new JsonFormatter()
            ]));
    }
}
