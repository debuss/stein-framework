<?php

namespace Application\Builder;

use JsonException;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\{ResponseFactoryInterface, ResponseInterface, StreamFactoryInterface};

class ResponseBuilder
{

    protected ResponseInterface $response;

    public function __construct(
        protected ResponseFactoryInterface $responseFactory,
        protected StreamFactoryInterface $streamFactory,
        protected ?TemplateRendererInterface $renderer = null
    ) {
        $this->response = $this->responseFactory->createResponse();
    }

    public function status(int $code): self
    {
        $this->response = $this->response->withStatus($code);
        return $this;
    }

    public function header(string $name, string $value): self
    {
        $this->response = $this->response->withHeader($name, $value);
        return $this;
    }

    public function html(string $html, ?int $status = null): ResponseInterface
    {
        if ($status) {
            $this->status($status);
        }

        $body = $this->streamFactory->createStream($html);

        return $this->response
            ->withHeader('Content-Type', 'text/html; charset=utf-8')
            ->withBody($body);
    }

    /**
     * @param array<mixed, mixed>|object|string|int|float $data
     * @throws JsonException
     */
    public function json(mixed $data, ?int $status = null): ResponseInterface
    {
        if ($status) {
            $this->status($status);
        }

        if (!is_string($data) || !json_validate($data)) {
            $data = json_encode($data, JSON_THROW_ON_ERROR);
        }

        $body = $this->streamFactory->createStream($data);

        return $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withBody($body);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function view(string $template, array $data = [], int $status = 200): ResponseInterface
    {
        if (!$this->renderer) {
            throw new \RuntimeException('No template engine found...');
        }

        $content = $this->renderer->render($template, $data);

        return $this->html($content, $status);
    }

    public function redirect(string $url, int $status = 302): ResponseInterface
    {
        return $this->response
            ->withStatus($status)
            ->withHeader('Location', $url);
    }
}
