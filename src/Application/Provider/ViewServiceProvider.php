<?php

namespace Application\Provider;

use Awareness\TemplateRendererAwareInterface;
use League\Container\Container;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Plates\{Engine};
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Mezzio\Plates\PlatesRenderer;
use Mezzio\Template\TemplateRendererInterface;

class ViewServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{

    public function boot(): void
    {
        /** @var Container $container */
        $container = $this->getContainer();

        $container->afterResolve(
            TemplateRendererAwareInterface::class,
            fn (TemplateRendererAwareInterface $controller) => $controller->setTemplateRenderer(
                $this->getContainer()->get(TemplateRendererInterface::class)
            )
        );
    }

    public function provides(string $id): bool
    {
        return $id === TemplateRendererInterface::class;
    }

    public function register(): void
    {
        $this
            ->getContainer()
            ->add(
                TemplateRendererInterface::class,
                function (): TemplateRendererInterface {
                    $plates = new Engine(storage_path() . '/views');

                    return new PlatesRenderer($plates);
                }
            );
    }
}
