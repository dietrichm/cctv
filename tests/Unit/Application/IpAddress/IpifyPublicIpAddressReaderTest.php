<?php

namespace Detroit\Cctv\Tests\Unit\Application\IpAddress;

use Detroit\Cctv\Application\IpAddress\IpifyPublicIpAddressReader;
use PHPUnit\Framework\TestCase;

final class IpifyPublicIpAddressReaderTest extends TestCase
{
    /**
     * @var IpifyPublicIpAddressReader
     */
    private $reader;

    public function setUp()
    {
        $this->reader = new IpifyPublicIpAddressReader();
    }

    /**
     * @test
     */
    public function itReturnsCurrentPublicIpAddress()
    {
        $ipAddress = $this->reader->get();

        $this->markTestIncomplete();
    }
}
