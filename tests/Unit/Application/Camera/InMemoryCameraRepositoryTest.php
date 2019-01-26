<?php

namespace Detroit\Cctv\Tests\Unit\Application\Camera;

use Detroit\Cctv\Application\Camera\InMemoryCameraRepository;
use Detroit\Cctv\Domain\Camera\Camera;
use Detroit\Cctv\Domain\Camera\CameraNotFound;
use Detroit\Cctv\Tests\CameraBuilder;
use PHPUnit\Framework\TestCase;

final class InMemoryCameraRepositoryTest extends TestCase
{
    /**
     * @var InMemoryCameraRepository
     */
    private $repository;

    /**
     * @var Camera
     */
    private $cameraOne;

    /**
     * @var Camera
     */
    private $cameraTwo;

    public function setUp()
    {
        $this->cameraOne = CameraBuilder::create()
            ->withName('foo')
            ->withoutRebootUri()
            ->build();
        $this->cameraTwo = CameraBuilder::create()
            ->withName('bar')
            ->withRebootUri('https://example.org/reboot1')
            ->build();

        $this->repository = new InMemoryCameraRepository([
            $this->cameraOne,
            $this->cameraTwo,
        ]);
    }

    /**
     * @test
     */
    public function itReturnsAllCameras()
    {
        $this->assertEquals(
            [
                $this->cameraOne,
                $this->cameraTwo,
            ],
            $this->repository->findAll()
        );
    }

    /**
     * @test
     */
    public function itReturnsCameraByName()
    {
        $this->assertEquals(
            $this->cameraOne,
            $this->repository->findByName('foo')
        );
    }

    /**
     * @test
     */
    public function itThrowsExceptionWhenCameraDoesNotExist()
    {
        $this->expectExceptionObject(CameraNotFound::withName('nonexistant'));

        $this->repository->findByName('nonexistant');
    }

    /**
     * @test
     */
    public function itReturnsRebootableCameras()
    {
        $this->assertEquals(
            [
                $this->cameraTwo,
            ],
            $this->repository->findRebootable()
        );
    }
}
