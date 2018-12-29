<?php

namespace Detroit\Cctv\Tests\Unit\Application\Camera;

use Detroit\Cctv\Application\Camera\InMemoryCameraRepository;
use Detroit\Cctv\Domain\Camera\Camera;
use League\Uri\Uri;
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
        $this->cameraOne = new Camera(
            'foo',
            Uri::createFromString('http://example.org/foo')
        );
        $this->cameraTwo = new Camera(
            'bar',
            Uri::createFromString('http://example.org/bar')
        );

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
}
