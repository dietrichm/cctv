<?php

namespace Detroit\Cctv\Domain\IpAddress;

final class IpAddress
{
    /**
     * @var string
     */
    private $ipAddress;

    public function __construct(string $ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }
}
