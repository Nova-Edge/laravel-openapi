<?php

namespace NovaEdge\LaravelOpenApi\Tests;

use GoldSpecDigital\ObjectOrientedOAS\OpenApi;
use NovaEdge\LaravelOpenApi\Generator;
use NovaEdge\LaravelOpenApi\OpenApiServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            OpenApiServiceProvider::class,
        ];
    }

    protected function generate(): OpenApi
    {
        return $this->app[Generator::class]->generate();
    }
}
