<?php

namespace Detroit\Cctv\Domain\IpAddress;

use InvalidArgumentException;

final class IpAddress
{
    /**
     * @var string
     */
    private $ipAddress;

    public function __construct(string $ipAddress)
    {
        if (!self::isValid($ipAddress)) {
            throw new InvalidArgumentException(
                'Invalid IP address ' . $ipAddress
            );
        }

        $this->ipAddress = $ipAddress;
    }

    private static function isValid(string $ipAddress): bool
    {
        return filter_var($ipAddress, FILTER_VALIDATE_IP);
    }
}
