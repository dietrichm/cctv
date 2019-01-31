<?php

namespace Detroit\Cctv\Tests\Unit\Application\IpAddress;

use Detroit\Cctv\Application\IpAddress\NeostradaPublicIpAddressUpdater;
use PHPUnit\Framework\TestCase;

final class NeostradaPublicIpAddressUpdaterTest extends TestCase
{
    /**
     * @var NeostradaPublicIpAddressUpdater
     */
    private $updater;

    public function setUp()
    {
        $this->updater = new NeostradaPublicIpAddressUpdater();
    }
}
