<?php

namespace Application\Provider;

use Application\Controller\Controller;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Plates\{Engine};
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Mezzio\Plates\PlatesRenderer;
use Mezzio\Template\TemplateRendererInterface;

class ViewServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{

    public function boot(): void
    {
        $this
            ->getContainer()
            ->inflector(
                Controller::class,
                fn (Controller $controller) => $controller->setTemplateRenderer(
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
