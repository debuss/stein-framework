## ⚡ Stein

**The PSR-assembled, FrankenPHP-ready, high-performance micro-framework.**

**Stein** is a "Modern Prometheus" experiment in PHP. Instead of reinventing the wheel, Stein stitches together
the most powerful, industry-standard PSR components into a cohesive, lightning-fast organism.

Born to run in **FrankenPHP’s Worker Mode**, Stein is lean, mean, and obsessed with clean architecture (DDD).

## 🧪 The "Parts" (Stitched with ❤️)

Stein doesn't hide its seams. It’s proud to be built on the shoulders of giants:

- **Container:** `league/container` (with auto-wiring & inflectors).
- **HTTP Factory:** `nyholm/psr7` (ultra-fast PSR-7/17).
- **Routing:** `fast-route` (via `middlewares/fast-route`).
- **Logging:** `monolog` (pre-configured for Docker/K8s).
- **Server:** Native **FrankenPHP** worker loop integration.

## 🚀 Key Concepts

#### 1. Attribute-Based Routing

Forget messy configuration files. Stein uses its own `AttributeRouteLoader` to scan your controllers. 
Just drop an attribute on your class, and Stein’s heart starts beating.

```php
class UserController extends Controller
{
    
    #[Route('/api/v1/users[/{id:\d+}]', method: ['GET'])]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // Your logic here
    }
}
```

#### 2. The Worker-First Mindset

Stein is built for 2026. It stays in memory between requests thanks to FrankenPHP. Our `worker.php` handles the
loop, and keeps your app screamingly fast by avoiding the "boot-everything-every-time"
tax of traditional FPM.

#### 3. Dependency Injection via Awareness

We hate bloated constructors. Stein uses Inflectors. If your controller implements `LoggerAwareInterface`, the
container automatically breathes life (and a Logger) into it. No manual wiring needed.  
The same goes for other PSR interfaces like `ResponseFactoryInterface`, `StreamFactoryInterface`, and more.

#### 4. The ResponseBuilder (The DX Special)

Standard PSR-7 can be a bit... talkative. Stein provides a fluent `ResponseBuilder` so you can write:

```php
return $this->response()
    ->status(201)
    ->json(['message' => 'It is alive!']);
```

## 💉 Smart Dependency Injection (The Inflector Pattern)

Stein leverages `league/container` Inflectors to keep your code clean and decoupled. In many frameworks,
you end up bloating your constructor with logging, factory, or caching services.

**Not here.**

If your class needs a specific PSR implementation, you simply implement the corresponding `AwareInterface`.
Stein detects it and "inflects" the dependency into your class via setters.

**Why this is better:**

- **Clean Constructors:** Your `__construct` only contains what's strictly necessary for your business logic (like Repositories).
- **PSR-Ready:** Easily swap any implementation (Monolog for logging, Nyholm for PSR-17 factories, Redis for PSR-6 caching).
- **Automatic Wiring:** No need to manually define how to inject the logger or factory into every single controller.

**Example:**

Just by implementing these interfaces, your controller is automatically equipped:

```php
use Awareness\{ResponseFactoryAwareInterface,
    StreamFactoryAwareInterface,
    ResponseFactoryAwareTrait,
    StreamFactoryAwareTrait}
use Psr\Log\{LoggerAwareInterface, LoggerAwareTrait};

class MyController extends Controller implements
    LoggerAwareInterface,
    ResponseFactoryAwareInterface,
    StreamFactoryAwareInterface
{

    // The Container will automatically call setLogger(), setResponseFactory(), etc.
    use LoggerAwareTrait,
        ResponseFactoryAwareTrait,
        StreamFactoryAwareTrait;

    public function handle(ServerRequestInterface $request): ResponseInterface 
    {
        $this->logger->info("Handling request!");
        
        // Use the injected factories via our fluent ResponseBuilder
        return $this->response()->json(['status' => 'ok']);
    }
}
```

An `AwareInterface` + `AwareTrait` combo exists for most common PSR interfaces.

## 🔌 Plug-and-Play Extensibility

Stein's architecture makes it incredibly easy to add new capabilities. Want to add **PSR-6 Caching** or 
**PSR-14 Event Dispatching**? You don't need to refactor your existing controllers.

Just follow the Stein "Assembly" pattern:

1. **Define your Provider:** Create a Service Provider that registers your new service (e.g., Redis Cache).
2. **Add an Inflector:** Tell the container that any class implementing `CacheAwareInterface` should receive the Cache service.
3. **Implement & Use:** Simply add the interface to your controller.

**Example of adding a new "Part" to the monster:**

```php
class LoggingServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{

    public function boot(): void
    {
        $this
            ->getContainer()
            ->inflector(
                LoggerAwareInterface::class,
                fn (LoggerAwareInterface $class) => $class->setLogger(
                    $this->getContainer()->get(Logger::class)->withName(get_class($class))
                ));
    }
    
    // ....
}
```

Now, any controller or class in your application can become "Logger-Aware" just by tagging it with the
interface. No constructor changes, no mess.

## 🎨 Powerful Templating (via Mezzio)

Stein doesn't force a template engine on you. Thanks to the **Mezzio Template** bridge, you can choose your
favorite weapon:

- `composer require mezzio/mezzio-twigrenderer` (for Twig)
- `composer require mezzio/mezzio-platesrenderer` (for Plates)

**Usage in Controller:**

```php
public function handle(ServerRequestInterface $request): ResponseInterface
{
    return $this->response()->view('profile', ['name' => 'Frankie']);
}
```

## 📂 Project Structure (DDD Inspired)

```text
src/
├── Application    # Controllers, Service Providers, Response Builders
├── Domain         # The heart of your app (Entities, Repository Interfaces)
└── Infrastructure # Database implementations, Third-party adapters
```

## 🛠️ Quick Start

1. **Create your project:**

```php
composer create-project stein/stein monstruous-app
cd monstruous-app
```

2. **Spin it up with Docker:**

```shell
docker-compose up -d
# or
composer start
```

3. **Create a Routed Controller :**

```php
namespace Application\Controller;

use Application\Attributes\Route;
use Psr\Http\Message\ResponseInterface;

class HomeController extends Controller 
{

    #[Route('/welcome')] // GET method by default
    public function handle(ServerRequestInterface $request): ResponseInterface 
    {
        return $this->response()->json(['message' => 'It is alive!']);
    }
}
```

> You still can add routes manually if needed (`config/routes.php`):
> 
> ```php
> $collector->addRoute('GET', '/users/{id:\d+}', UserController::class);
> ```


4. **Profit.**

Stein automatically discovers your routes and wires them to the FastRoute dispatcher.

## 🧠 Why Stein?

Because you want the control of a custom framework without the technical debt. By using only PSR-compliant
bricks, you can swap any part of Stein at any time.

**It’s not just a framework; it’s an assembly of excellence.**

## ⚖️ License

MIT. Go ahead, build your monster.
