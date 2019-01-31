<?php

namespace Detroit\Cctv\Domain\IpAddress;

interface PublicIpAddressUpdater
{
    public function set(IpAddress $ipAddress): void;
}
