<?php

namespace Detroit\Cctv\Infrastructure;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

final class LoggingServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        LoggerInterface::class,
    ];

    public function register(): void
    {
        $this->getContainer()->share(LoggerInterface::class, function () {
            $logger = new Logger('cctv');
            $logger->pushHandler(new StreamHandler(getenv('LOG_FILE')));

            return $logger;
        });
    }
}
