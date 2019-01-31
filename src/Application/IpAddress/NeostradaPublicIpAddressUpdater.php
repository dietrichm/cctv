<?php

namespace Detroit\Cctv\Application\IpAddress;

use Detroit\Cctv\Domain\IpAddress\IpAddress;
use Detroit\Cctv\Domain\IpAddress\PublicIpAddressUpdater;

final class NeostradaPublicIpAddressUpdater implements PublicIpAddressUpdater
{
    public function set(IpAddress $ipAddress): void
    {
    }
}
