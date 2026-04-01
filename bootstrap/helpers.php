<?php

use Psr\Http\Message\ResponseInterface;

/**
 * Returns the path to the root app directory, appended with extra path.
 */
function app_path(string ...$paths): string
{
    $path = array_reduce($paths, fn($acc, $cur) => $acc.'/'.ltrim($cur, ' /\\'), '');

    return __ROOT_DIR__.$path;
}

/**
 * Returns the path to the source directory, appended with extra path.
 */
function source_path(string ...$paths): string
{
    return app_path('src', ...$paths);
}

/**
 * Returns the path to the config directory, appended with extra path.
 */
function config_path(string ...$paths): string
{
    return app_path('config', ...$paths);
}

/**
 * Returns the path to the storage directory, appended with extra path.
 */
function storage_path(string ...$paths): string
{
    return app_path('storage', ...$paths);
}

/**
 * Returns the path to the cache directory, appended with extra path.
 */
function cache_path(string ...$paths): string
{
    return storage_path('cache', ...$paths);
}

/**
 * Returns the path to the logs' directory, appended with extra path.
 */
function logs_path(string ...$paths): string
{
    return storage_path('logs', ...$paths);
}

/**
 * Emits the given response.
 *
 * @link https://github.com/http-interop/response-sender/blob/master/src/functions.php
 */
function emit(ResponseInterface $response): void
{
    // Headers
    foreach ($response->getHeaders() as $name => $values) {
        foreach ($values as $value) {
            header(sprintf(
                '%s: %s',
                ucwords($name, '-'), $value),
                false
            );
        }
    }

    // Status Line
    $http_line = sprintf(
        'HTTP/%s %s %s',
        $response->getProtocolVersion(),
        $response->getStatusCode(),
        $response->getReasonPhrase()
    );

    header($http_line, true, $response->getStatusCode());

    // Body
    $stream = $response->getBody();
    if ($stream->isSeekable()) {
        $stream->rewind();
    }

    if (!$stream->isReadable()) {
        echo $stream;
        return;
    }

    $length = 1024 * 8;
    while (!$stream->eof()) {
        echo $stream->read($length);
    }
}
