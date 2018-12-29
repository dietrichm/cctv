<?php

namespace Detroit\Cctv\Tests\Unit\Application\Camera;

use Detroit\Cctv\Application\Camera\InMemoryCameraRepository;
use PHPUnit\Framework\TestCase;

final class InMemoryCameraRepositoryTest extends TestCase
{
    /**
     * @var InMemoryCameraRepository
     */
    private $repository;

    public function setUp()
    {
        $this->repository = new InMemoryCameraRepository();
    }

    /**
     * @test
     */
    public function itReturnsAllCameras()
    {
        $cameras = $this->repository->findAll();

        $this->assertEquals([], $cameras);
    }
}
