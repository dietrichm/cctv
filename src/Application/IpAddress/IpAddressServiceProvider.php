<?php

namespace Detroit\Cctv\Application\IpAddress;

use Detroit\Cctv\Domain\IpAddress\PublicIpAddressReader;
use Detroit\Cctv\Domain\IpAddress\PublicIpAddressUpdater;
use GuzzleHttp\Client;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Psr\Log\LoggerInterface;

final class IpAddressServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        PublicIpAddressReader::class,
        PublicIpAddressUpdater::class,
    ];

    public function register(): void
    {
        $this->getContainer()->share(PublicIpAddressReader::class, function () {
            return new HttpPublicIpAddressReader(
                $this->getContainer()->get(Client::class),
                $this->getContainer()->get(LoggerInterface::class),
                'https://api.ipify.org'
            );
        });

        $this->getContainer()->share(PublicIpAddressUpdater::class, function () {
            return new NeostradaPublicIpAddressUpdater(
                $this->getContainer()->get(Client::class),
                $this->getContainer()->get(LoggerInterface::class),
                getenv('NEOSTRADA_API_TOKEN'),
                (int) getenv('NEOSTRADA_DNS_ID'),
                (int) getenv('NEOSTRADA_RECORD_ID')
            );
        });
    }
}
