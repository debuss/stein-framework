<?php

namespace Application\Controller;

use Application\Builder\ResponseBuilder;
use Awareness\{ResponseFactoryAwareInterface,
    ResponseFactoryAwareTrait,
    StreamFactoryAwareInterface,
    StreamFactoryAwareTrait,
    TemplateRendererAwareInterface,
    TemplateRendererAwareTrait
};
use Psr\Log\{LoggerAwareInterface, LoggerAwareTrait};
use Psr\Http\Server\RequestHandlerInterface;

abstract class Controller implements
    LoggerAwareInterface,
    ResponseFactoryAwareInterface,
    StreamFactoryAwareInterface,
    TemplateRendererAwareInterface,
    RequestHandlerInterface
{

    use LoggerAwareTrait,
        ResponseFactoryAwareTrait,
        StreamFactoryAwareTrait,
        TemplateRendererAwareTrait;

    protected function response(): ResponseBuilder
    {
        return new ResponseBuilder(
            $this->responseFactory,
            $this->streamFactory,
            $this->templateRenderer
        );
    }
}
