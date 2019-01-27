<?php

namespace Detroit\Cctv\Tests\Unit\Domain\IpAddress;

use Detroit\Cctv\Domain\IpAddress\IpAddress;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class IpAddressTest extends TestCase
{
    /**
     * @test
     * @dataProvider providesValidIpAddresses
     */
    public function itCreatesValidIpAddress(string $ipAddress)
    {
        $ipAddress = new IpAddress($ipAddress);

        $this->assertInstanceOf(IpAddress::class, $ipAddress);
    }

    public function providesValidIpAddresses(): array
    {
        return [
            ['250.23.235.22'],
            ['5ab7:c057:1ecd:ace9:9e14:9e1:7b21:b013'],
        ];
    }

    /**
     * @test
     * @dataProvider providesInvalidIpAddresses
     */
    public function itDoesNotCreateInvalidIpAddress(string $ipAddress)
    {
        $this->expectExceptionObject(
            new InvalidArgumentException('Invalid IP address ' . $ipAddress)
        );

        new IpAddress($ipAddress);
    }

    public function providesInvalidIpAddresses(): array
    {
        return [
            ['250.23.235.999'],
            ['5ab7:c057:1ecd:ace9:9e14:9z1:7b21:b013'],
            ['250.23.235'],
            ['5ab7:c057:1ecd:ace9:9e14:9z1:7b21'],
            ['some string'],
            [12345],
            [12.34],
            [true],
        ];
    }
}
