<?php

namespace Application\Routing\Attribute;

use FastRoute\RouteCollector;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionException;
use SplFileInfo;

class AttributeRouteLoader
{

    public function __construct(
        private string $namespace,
        private string $path
    ) {}

    public function load(RouteCollector $collector): void
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->path, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        /** @var SplFileInfo $file */
        foreach ($iterator as $file) {
            if ($file->isDir() || $file->getExtension() !== 'php') {
                continue;
            }

            // Convert file path to Class Name (PSR-4 assumption)
            $relativePath = str_replace([$this->path, '.php', '/'], ['', '', '\\'], $file->getPathname());
            $className = $this->namespace . trim($relativePath, '\\');

            if (!class_exists($className)) {
                continue;
            }

            $this->registerClassRoutes($collector, $className);
        }
    }

    private function registerClassRoutes(RouteCollector $collector, string $className): void
    {
        try {
            $reflectionClass = new ReflectionClass($className);

            foreach ($reflectionClass->getMethods() as $method) {
                $attributes = $method->getAttributes(Route::class);

                foreach ($attributes as $attribute) {
                    /** @var Route $routeInstance */
                    $routeInstance = $attribute->newInstance();

                    foreach ($routeInstance->methods as $httpMethod) {
                        $collector->addRoute($httpMethod, $routeInstance->path, $className);
                    }
                }
            }
        } catch (ReflectionException) {
            // Log or ignore
        }
    }
}
