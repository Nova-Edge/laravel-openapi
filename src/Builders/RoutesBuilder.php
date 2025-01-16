<?php

namespace Vyuldashev\LaravelOpenApi\Builders;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Vyuldashev\LaravelOpenApi\Attributes;
use Vyuldashev\LaravelOpenApi\Contracts\RoutesBuilderMiddleware;
use Vyuldashev\LaravelOpenApi\Middleware;
use Vyuldashev\LaravelOpenApi\RouteInformation;

class RoutesBuilder
{
    /**
     * @var Router
     */
    protected Router $router;

    /**
     * @param  Router  $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param  RoutesBuilderMiddleware[]  $middlewares
     * @return Collection
     */
    public function build(array $middlewares): Collection
    {
        /** @noinspection CollectFunctionInCollectionInspection */
        return collect($this->router->getRoutes())
            ->filter(static fn (Route $route) => $route->getActionName() !== 'Closure')
            ->map(static fn (Route $route) => RouteInformation::createFromRoute($route))
            ->map(static function (RouteInformation $route) use ($middlewares): RouteInformation {
                return Middleware::make($middlewares)
                    ->using(RoutesBuilderMiddleware::class)
                    ->send($route)
                    ->through(fn ($middleware, $route) => $middleware->after($route));
            })
            ->filter(static function (RouteInformation $route): bool {
                $pathItem = $route->controllerAttributes
                    ->first(static fn (object $attribute) => $attribute instanceof Attributes\PathItem);

                $operation = $route->actionAttributes
                    ->first(static fn (object $attribute) => $attribute instanceof Attributes\Operation);

                return $pathItem && $operation;
            });
    }
}
