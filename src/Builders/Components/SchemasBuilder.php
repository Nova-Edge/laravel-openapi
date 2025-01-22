<?php

namespace NovaEdge\LaravelOpenApi\Builders\Components;

use NovaEdge\LaravelOpenApi\Contracts\Reusable;
use NovaEdge\LaravelOpenApi\Factories\SchemaFactory;
use NovaEdge\LaravelOpenApi\Generator;

class SchemasBuilder extends Builder
{
    public function build(string $collection = Generator::COLLECTION_DEFAULT): array
    {
        return $this->getAllClasses($collection)
            ->filter(static function ($class) {
                return
                    is_a($class, SchemaFactory::class, true) &&
                    is_a($class, Reusable::class, true);
            })
            ->map(static function ($class) {
                /** @var SchemaFactory $instance */
                $instance = app($class);

                return $instance->build();
            })
            ->values()
            ->toArray();
    }
}
