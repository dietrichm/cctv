<?php

namespace Detroit\Cctv\Tests\Unit\Application\Camera;

use Detroit\Cctv\Application\Camera\RebootCamerasCommand;
use PHPUnit\Framework\TestCase;

final class RebootCamerasCommandTest extends TestCase
{
    /**
     * @var RebootCamerasCommand
     */
    private $command;

    public function setUp()
    {
        $this->command = new RebootCamerasCommand();
    }

    /**
     * @test
     */
    public function itIsConfiguredCorrectly()
    {
        $this->assertEquals('reboot-cameras', $this->command->getName());
        $this->assertEquals('Reboot all cameras', $this->command->getDescription());
    }
}
