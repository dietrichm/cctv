<?php

namespace Detroit\Cctv\Domain\IpAddress;

interface PublicIpAddressReader
{
    public function get(): IpAddress;
}
