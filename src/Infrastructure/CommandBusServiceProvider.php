<?php

namespace Detroit\Cctv\Infrastructure;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Tactician\CommandBus;
use League\Tactician\Container\ContainerLocator;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\HandleClassNameInflector;

final class CommandBusServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        CommandBus::class,
    ];

    public function register(): void
    {
        $this->getContainer()->share(CommandBus::class, function () {
            return new CommandBus([
                new CommandHandlerMiddleware(
                    new ClassNameExtractor(),
                    new ContainerLocator(
                        $this->getContainer(),
                        $this->getMapping()
                    ),
                    new HandleClassNameInflector()
                ),
            ]);
        });
    }

    private function getMapping(): array
    {
        return [];
    }
}
