<?php

namespace Detroit\Cctv\Infrastructure;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Tactician\CommandBus;

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
            return new CommandBus([]);
        });
    }
}
