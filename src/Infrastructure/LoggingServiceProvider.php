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

            $logFile = getenv('LOG_FILE');

            if ($logFile !== false) {
                $logger->pushHandler(new StreamHandler($logFile));
            }

            return $logger;
        });
    }
}
