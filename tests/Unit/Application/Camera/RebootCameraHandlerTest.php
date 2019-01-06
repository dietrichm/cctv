<?php

namespace Detroit\Cctv\Tests\Unit\Application\Camera;

use Detroit\Cctv\Application\Camera\RebootCameraHandler;
use Detroit\Cctv\Domain\Camera\RebootCameraCommand;
use PHPUnit\Framework\TestCase;

final class RebootCameraHandlerTest extends TestCase
{
    /**
     * @var RebootCameraHandler
     */
    private $handler;

    public function setUp()
    {
        $this->handler = new RebootCameraHandler();
    }

    /**
     * @test
     */
    public function itCallsCameraRebootUri()
    {
        $command = new RebootCameraCommand('foo');

        $this->handler->handleRebootCameraCommand($command);

        $this->markTestIncomplete();
    }
}
