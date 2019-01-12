<?php

namespace Detroit\Cctv\Infrastructure;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RavenHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Raven_Client;

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
            $ravenDsn = getenv('RAVEN_DSN');

            if ($logFile !== false) {
                $logger->pushHandler(new StreamHandler($logFile));
            }

            if ($ravenDsn !== false) {
                $ravenHandler = new RavenHandler(new Raven_Client($ravenDsn));
                $ravenHandler->setFormatter(
                    new LineFormatter("%message% %context% %extra%\n")
                );
                $logger->pushHandler($ravenHandler);
            }

            return $logger;
        });
    }
}
