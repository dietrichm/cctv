<?php

namespace Detroit\Cctv\Infrastructure;

use League\Container\ServiceProvider\AbstractServiceProvider;

final class LoggingServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
    ];

    public function register(): void
    {
    }
}
