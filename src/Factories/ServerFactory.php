<?php

namespace NovaEdge\LaravelOpenApi\Factories;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Server;

abstract class ServerFactory
{
    abstract public function build(): Server;
}
