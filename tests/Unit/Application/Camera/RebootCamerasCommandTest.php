<?php

namespace Detroit\Cctv\Tests\Unit\Application\Camera;

use Detroit\Cctv\Application\Camera\RebootCamerasCommand;
use Detroit\Cctv\Domain\Camera\CameraRepository;
use League\Tactician\CommandBus;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Output\Output;

final class RebootCamerasCommandTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $cameraRepository;

    /**
     * @var MockObject
     */
    private $commandBus;

    /**
     * @var RebootCamerasCommand
     */
    private $command;

    public function setUp()
    {
        $this->cameraRepository = $this->createMock(CameraRepository::class);
        $this->commandBus = $this->createMock(CommandBus::class);
        $this->command = new RebootCamerasCommand(
            $this->cameraRepository,
            $this->commandBus
        );
    }

    /**
     * @test
     */
    public function itIsConfiguredCorrectly()
    {
        $this->assertEquals('reboot-cameras', $this->command->getName());
        $this->assertEquals('Reboot all cameras', $this->command->getDescription());
    }

    /**
     * @test
     */
    public function itRebootsAllCameras()
    {
        $this->command->run(
            $this->createMock(Input::class),
            $this->createMock(Output::class)
        );

        $this->markTestIncomplete();
    }
}
