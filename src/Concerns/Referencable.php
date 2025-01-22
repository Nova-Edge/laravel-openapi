<?php

namespace NovaEdge\LaravelOpenApi\Concerns;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use InvalidArgumentException;
use NovaEdge\LaravelOpenApi\Contracts\Reusable;
use NovaEdge\LaravelOpenApi\Factories\CallbackFactory;
use NovaEdge\LaravelOpenApi\Factories\ParametersFactory;
use NovaEdge\LaravelOpenApi\Factories\RequestBodyFactory;
use NovaEdge\LaravelOpenApi\Factories\ResponseFactory;
use NovaEdge\LaravelOpenApi\Factories\SchemaFactory;
use NovaEdge\LaravelOpenApi\Factories\SecuritySchemeFactory;

trait Referencable
{
    static $buildReferencableSchemas = [];

    public static function ref(?string $objectId = null): Schema
    {
        $instance = app(static::class);

        if (! $instance instanceof Reusable) {
            throw new InvalidArgumentException('"'.static::class.'" must implement "'.Reusable::class.'" in order to be referencable.');
        }

        $baseRef = null;

        if ($instance instanceof CallbackFactory) {
            $baseRef = '#/components/callbacks/';
        } elseif ($instance instanceof ParametersFactory) {
            $baseRef = '#/components/parameters/';
        } elseif ($instance instanceof RequestBodyFactory) {
            $baseRef = '#/components/requestBodies/';
        } elseif ($instance instanceof ResponseFactory) {
            $baseRef = '#/components/responses/';
        } elseif ($instance instanceof SchemaFactory) {
            $baseRef = '#/components/schemas/';
        } elseif ($instance instanceof SecuritySchemeFactory) {
            $baseRef = '#/components/securitySchemes/';
        }

        return self::$buildReferencableSchemas[static::class . "_{$objectId}"] ??= Schema::ref($baseRef.$instance->build()->objectId, $objectId);
    }
}
