<?php

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
 * Creates a new stdClass object with the given properties and values.
 *
 * @param mixed ...$args A list of property-value pairs to set on the object. If a key is not a string, it will be ignored.
 */
function literal(...$args): stdClass
{
    $object = new stdClass();

    foreach ($args as $property => $value) {
        if (!is_string($property)) {
            continue;
        }

        $object->$property = $value;
    }

    return $object;
}

/**
 * Returns the current date and time as a DateTimeImmutable object.
 */
function now(): DateTimeImmutable
{
    return new DateTimeImmutable();
}

/**
 * Executes the given callback only once and returns its result for the duration of the request.
 * Subsequent calls will return the cached result.
 *
 * @return mixed The result of the callback execution, or the cached result on subsequent calls.
 */
function once(callable|Closure $callback): mixed
{
    static $result = null;
    if ($result !== null) {
        return $result;
    }

    return $result = $callback();
}

/**
 * Transforms the given value using the provided callback, or returns a default value if the input is null.
 */
function transform(mixed $value, callable|Closure $callback, mixed $default = null): mixed
{
    if ($value === null) {
        return $default;
    }

    return $callback($value);
}

/**
 * Returns the given value, or applies the callback to it if provided.
 * If $value is a callable, additional parameters will be passed to the closure as arguments
 */
function value(mixed $value, ...$args): mixed
{
    if (is_callable($value)) {
        return $value(...$args);
    }

    return $value;
}

/**
 * Returns the given value, or applies the callback to it if provided.
 */
function with(mixed $value, null|callable|Closure $callback = null): mixed
{
    if ($callback === null) {
        return $value;
    }

    return $callback($value);
}
