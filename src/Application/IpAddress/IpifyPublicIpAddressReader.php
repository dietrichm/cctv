<?php

namespace Detroit\Cctv\Application\IpAddress;

use Detroit\Cctv\Domain\IpAddress\IpAddress;
use Detroit\Cctv\Domain\IpAddress\PublicIpAddressReader;

final class IpifyPublicIpAddressReader implements PublicIpAddressReader
{
    public function get(): IpAddress
    {
        return new IpAddress('127.0.0.1');
    }
}
