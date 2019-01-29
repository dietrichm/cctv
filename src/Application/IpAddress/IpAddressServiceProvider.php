<?php

namespace Detroit\Cctv\Application\IpAddress;

use Detroit\Cctv\Domain\IpAddress\PublicIpAddressReader;
use GuzzleHttp\Client;
use League\Container\ServiceProvider\AbstractServiceProvider;

final class IpAddressServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        PublicIpAddressReader::class,
    ];

    public function register(): void
    {
        $this->getContainer()->share(PublicIpAddressReader::class, function () {
            return new HttpPublicIpAddressReader(
                $this->getContainer()->get(Client::class),
                'https://api.ipify.org'
            );
        });
    }
}
