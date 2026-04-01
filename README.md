## ⚡ Stein

**The PSR-assembled, FrankenPHP-ready, high-performance micro-framework.**

**Stein** is a "Modern Prometheus" experiment in PHP. Instead of reinventing the wheel, Stein stitches together
the most powerful, industry-standard PSR components into a cohesive, lightning-fast organism.

Born to run in **FrankenPHP's Worker Mode**, Stein is lean, mean, and obsessed with clean architecture (DDD).

## 🧪 The "Parts" (Stitched with ❤️)

Stein doesn't hide its seams. It's proud to be built on the shoulders of giants:

- **Container:** `league/container` (with auto-wiring & `afterResolve` hooks).
- **Routing:** `league/route` (with route caching for production).
- **HTTP Messages:** `laminas/laminas-diactoros` (PSR-7/17 factories & responses).
- **Middleware:** `middlewares/error-handler`, `middlewares/payload`.
- **Logging:** `monolog` (pre-configured for Docker/K8s, JSON output to stderr).
- **Templating:** `mezzio/mezzio-platesrenderer` (swappable via Mezzio Template bridge).
- **Configuration:** `borschphp/config` (INI-based `.env` aggregation).
- **Awareness:** `debuss-a/awareness` (PSR-aware interfaces & traits for clean DI).
- **Server:** Native **FrankenPHP** worker loop integration.

## 🚀 Key Concepts

#### 1. Route Configuration

Routes are defined in `config/routes.php` as a simple closure receiving the router and container.
They support grouping, per-group middleware, and FastRoute-style path patterns.

```php
// config/routes.php
return static function (RouterInterface $router, ContainerInterface $container): void {

    $router->map('GET', '/', HomePageController::class);

    $router->group('/api/v1', function (RouteGroup $group) {
        $group->map('GET', '/users[/{id:\d+}]', UserController::class);
    })->lazyMiddlewares([JsonPayload::class]);

};
```

In production (`APP_ENV=production`), routes are automatically cached to a file via `league/route`'s
`CachedRouter`, giving you zero-overhead routing after the first request.

#### 2. The Worker-First Mindset

Stein is built for 2026. It stays in memory between requests thanks to FrankenPHP. `worker.php` handles
the request loop and boots the container **once**, keeping your app screamingly fast by avoiding the
"boot-everything-every-time" tax of traditional FPM.

A configurable restart threshold prevents memory creep:

```ini
; .env
FRANKENPHP_NB_REQUEST_TO_RESTART=1000
```

#### 3. Dependency Injection via Awareness

Stein uses `league/container`'s `afterResolve` hooks to automatically inject PSR services into any class
that implements the corresponding `AwareInterface` — with **zero constructor pollution**.

If your class implements `LoggerAwareInterface`, the container calls `setLogger()` automatically after
resolving it. The same pattern works for `TemplateRendererAwareInterface`, `ResponseFactoryAwareInterface`,
and any custom aware interface you define.

#### 4. DDD Project Structure

Stein scaffolds a clean Domain-Driven Design layout out of the box:

```text
src/
├── Application    # Controllers, Service Providers, Use Cases
├── Domain         # Entities, Repository Interfaces, Value Objects, Domain Exceptions
└── Infrastructure # Repository implementations, third-party adapters
```

## 💉 Smart Dependency Injection (The Awareness Pattern)

If your class needs a PSR service, implement the corresponding `AwareInterface` and Stein's container
injects it automatically after resolution — no manual wiring, no constructor bloat.

**The base `Controller` class demonstrates this:**

```php
namespace Application\Controller;

use Awareness\{TemplateRendererAwareInterface, TemplateRendererAwareTrait};
use Psr\Log\{LoggerAwareInterface, LoggerAwareTrait};
use Psr\Http\Server\RequestHandlerInterface;

abstract class Controller implements
    LoggerAwareInterface,
    TemplateRendererAwareInterface,
    RequestHandlerInterface
{
    // The container calls setLogger() and setTemplateRenderer() automatically.
    use LoggerAwareTrait,
        TemplateRendererAwareTrait;
}
```

**A controller using those injected services:**

```php
class HomePageController extends Controller
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->logger->info('Displaying home page');

        return new HtmlResponse($this->templateRenderer->render('home'));
    }
}
```

**Why this is better than constructor injection for cross-cutting concerns:**

- **Clean Constructors:** Your `__construct` only contains what's strictly necessary for your business logic (like Repositories).
- **PSR-Ready:** Easily swap any implementation (Monolog → another PSR-3 logger, Plates → Twig).
- **Automatic Wiring:** No need to manually define how to inject the logger or template renderer into every controller.

## 🔌 Plug-and-Play Extensibility

Adding a new capability (e.g., PSR-6 Caching, PSR-14 Event Dispatching) follows a simple three-step pattern:

1. **Define your Provider:** Create a Service Provider that registers your service and hooks `afterResolve`.
2. **Implement & Use:** Add the `AwareInterface` and `AwareTrait` to any class that needs the service.

**Example — wiring the logger into any `LoggerAwareInterface` class:**

```php
class LoggingServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    public function boot(): void
    {
        $this
            ->getContainer()
            ->afterResolve(
                LoggerAwareInterface::class,
                fn (LoggerAwareInterface $class) => $class->setLogger(
                    $this->getContainer()->get(Logger::class)->withName(get_class($class))
                )
            );
    }

    // register() binds LoggerInterface and Logger::class ...
}
```

Now any class implementing `LoggerAwareInterface` is automatically equipped with a logger named after the
class itself — no constructor changes, no manual wiring.

## 🎨 Powerful Templating (via Mezzio)

Stein doesn't force a template engine on you. Thanks to the **Mezzio Template** bridge, you can swap
engines with a single `composer require`:

- `composer require mezzio/mezzio-platesrenderer` ← included by default (Plates)
- `composer require mezzio/mezzio-twigrenderer` (for Twig)
- `composer require mezzio/mezzio-laminasviewrenderer` (for Laminas View)

Just update `ViewServiceProvider` to bind the new renderer — nothing else changes.

**Usage in a Controller:**

```php
public function handle(ServerRequestInterface $request): ResponseInterface
{
    return new HtmlResponse($this->templateRenderer->render('profile', ['name' => 'Frankie']));
}
```

Templates live in `storage/views/` and support layouts out of the box:

```php
// storage/views/home.php
<?php $this->layout('layout', ['title' => 'Welcome']) ?>
<h1>It's Alive!</h1>
```

## 📂 Project Structure

```text
.
├── bootstrap/          # Global defines and helper functions (app_path, emit, ...)
├── config/
│   ├── container.php   # Container bootstrap — service providers & bindings
│   ├── middlewares.php # Global middleware stack
│   └── routes.php      # Route definitions
├── public/
│   ├── index.php       # FPM / dev server entry point
│   └── worker.php      # FrankenPHP worker entry point
├── src/
│   ├── Application/    # Controllers, Service Providers, Use Cases
│   ├── Domain/         # Entities, Repository Interfaces, Value Objects
│   └── Infrastructure/ # Repository implementations, adapters
├── storage/
│   ├── cache/          # Route cache (production)
│   ├── logs/
│   └── views/          # Plates templates
└── stubs/              # PHPStan & FrankenPHP function stubs
```

## 🛠️ Quick Start

1. **Create your project:**

```shell
composer create-project stein/stein my-app
cd my-app
cp .env.example .env
```

2. **Spin it up with Docker (FrankenPHP worker mode):**

```shell
composer start
# or
docker compose up --build
```

3. **Or run locally with the built-in PHP server:**

```shell
composer serve
```

4. **Create a Controller:**

```php
namespace Application\Controller;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};

class WelcomeController extends Controller
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->logger->info('Welcome!');

        return new JsonResponse(['message' => 'It is alive!']);
    }
}
```

5. **Register its route in `config/routes.php`:**

```php
$router->map('GET', '/welcome', WelcomeController::class);
```

6. **Profit.**

## 🧠 Why Stein?

Because you want the control of a custom framework without the technical debt. By using only PSR-compliant
bricks, you can swap any part of Stein at any time.

- Swap `laminas-diactoros` for any other PSR-7/17 implementation.
- Swap `Plates` for `Twig` in one `composer require` + one service provider change.
- Swap `Monolog` for any PSR-3 logger.
- Add PSR-6 caching, PSR-14 events, or any other PSR service by following the Awareness pattern.

**It's not just a framework; it's an assembly of excellence.**

## ⚖️ License

MIT. Go ahead, build your monster.
