<?php

/**
 * FrankenPHP functions
 */

use Psr\Http\Message\ServerRequestInterface;

if (!function_exists('frankenphp_handle_request')) {
    /**
     * Handle incoming HTTP requests.
     *
     * @param callable $callback Callback to call for every incoming request.
     * @return bool Returns false when worker should stop.
     */
    function frankenphp_handle_request(callable $callback): bool {}
}

if (!function_exists('frankenphp_psr7_incoming_request')) {
    /**
     * Get current request in PSR-7 format.
     *
     * @return ServerRequestInterface
     */
    function frankenphp_psr7_incoming_request(): ServerRequestInterface {}
}

if (!function_exists('frankenphp_finish_request')) {
    /**
     * Terminate the HTTP request and send the response to the client while continuing script execution.
     */
    function frankenphp_finish_request(): void {}
}
