<?php

namespace App\Console\Commands\Scaffold;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Log;

class ServiceMakeCommand extends GeneratorCommand
{
    protected $signature = 'imake:service
                            {name : service name, e.g. SampleService}';

    protected $description = 'Create a new Service class.';

    protected $type = 'Service';

    protected function getStub()
    {
        return  __DIR__ . '/stubs/services/service.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Services';
    }

    protected function buildClass($name)
    {
        $replace = $this->buildReplacements();

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }

    protected function buildReplacements()
    {
        $modelName = trim($this->option('model'));
        // $modelName = $modelName ?: $this->argument('name');
        Log::info($modelName);
        return [
            'DummyRepositoryClass' => $modelName . 'Repository',
            'DummyValidatorClass'  => $modelName . 'Validator'
        ];
    }
}
