<?php

namespace NovaEdge\LaravelOpenApi\Contracts;

use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use NovaEdge\LaravelOpenApi\RouteInformation;

interface PathMiddleware
{
    public function before(RouteInformation $routeInformation): void;

    public function after(PathItem $pathItem): PathItem;
}
