<?php

namespace Detroit\Cctv\Tests\Unit\Application\Camera;

use Detroit\Cctv\Application\Camera\RebootCamerasCommand;
use Detroit\Cctv\Domain\Camera\CameraRepository;
use Detroit\Cctv\Domain\Camera\CameraUnavailable;
use Detroit\Cctv\Domain\Camera\RebootCameraCommand;
use Detroit\Cctv\Tests\CameraBuilder;
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
    public function itRebootsRebootableCameras()
    {
        $cameraOne = CameraBuilder::create()
            ->withName('foo')
            ->build();
        $cameraTwo = CameraBuilder::create()
            ->withName('bar')
            ->build();

        $this->cameraRepository->expects($this->once())
            ->method('findRebootable')
            ->willReturn([
                $cameraOne,
                $cameraTwo,
            ]);

        $this->commandBus->expects($this->exactly(2))
            ->method('handle')
            ->withConsecutive(
                [new RebootCameraCommand('foo')],
                [new RebootCameraCommand('bar')]
            );

        $this->command->run(
            $this->createMock(Input::class),
            $this->createMock(Output::class)
        );
    }

    /**
     * @test
     */
    public function itShowsInfoWhenCameraIsRebooted()
    {
        $camera = CameraBuilder::create()
            ->build();

        $this->cameraRepository->method('findRebootable')
            ->willReturn([
                $camera,
            ]);

        $output = $this->createMock(Output::class);

        $output->expects($this->once())
            ->method('writeln')
            ->with('<info>Camera foo rebooted.</info>');

        $this->command->run(
            $this->createMock(Input::class),
            $output
        );
    }

    /**
     * @test
     */
    public function itShowsWarningWhenCameraIsUnavailable()
    {
        $camera = CameraBuilder::create()
            ->build();

        $this->cameraRepository->method('findRebootable')
            ->willReturn([
                $camera,
            ]);

        $exception = CameraUnavailable::withName('foo');
        $this->commandBus->method('handle')
            ->willThrowException($exception);

        $output = $this->createMock(Output::class);

        $output->expects($this->once())
            ->method('writeln')
            ->with('<comment>Could not reboot: ' . $exception->getMessage() . '</comment>');

        $this->command->run(
            $this->createMock(Input::class),
            $output
        );
    }
}
