<?php

namespace Application\Controller;

use Mezzio\Template\TemplateRendererInterface;
use Awareness\{TemplateRendererAwareInterface, TemplateRendererAwareTrait};
use Psr\Log\{LoggerAwareInterface, LoggerAwareTrait, LoggerInterface};
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @phpstan-property LoggerInterface $logger
 * @phpstan-property TemplateRendererInterface $templateRenderer
 */
abstract class Controller implements
    LoggerAwareInterface,
    TemplateRendererAwareInterface,
    RequestHandlerInterface
{

    use LoggerAwareTrait,
        TemplateRendererAwareTrait;
}
