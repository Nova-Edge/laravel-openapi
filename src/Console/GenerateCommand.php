<?php

declare(strict_types=1);

namespace NovaEdge\LaravelOpenApi\Console;

use Illuminate\Console\Command;
use NovaEdge\LaravelOpenApi\Generator;

class GenerateCommand extends Command
{
    protected $signature = 'openapi:generate {collection=default}';
    protected $description = 'Generate OpenAPI specification';

    public function handle(Generator $generator): void
    {
        $collectionExists = collect(config('openapi.collections'))->has($this->argument('collection'));

        if (! $collectionExists) {
            $this->error('Collection "'.$this->argument('collection').'" does not exist.');

            return;
        }

        $this->line(
            $generator
                ->generate($this->argument('collection'))
                ->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }
}
