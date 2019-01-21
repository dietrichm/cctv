<?php

namespace Detroit\Cctv\Tests\Unit\Application\Camera;

use Detroit\Cctv\Application\Camera\EnvironmentCameraFactory;
use Detroit\Cctv\Domain\Camera\Camera;
use League\Uri\Uri;
use PHPUnit\Framework\TestCase;

final class EnvironmentCameraFactoryTest extends TestCase
{
    /**
     * @var EnvironmentCameraFactory
     */
    private $cameraFactory;

    public function setUp()
    {
        $this->cameraFactory = new EnvironmentCameraFactory();
    }

    /**
     * @test
     */
    public function itCreatesAllCamerasFromEnvironment()
    {
        putenv('CAMERA_1_NAME=foo');
        putenv('CAMERA_1_SNAPSHOT_URI=https://via.placeholder.com/500');
        putenv('CAMERA_1_REBOOT_URI');
        putenv('CAMERA_2_NAME=bar');
        putenv('CAMERA_2_SNAPSHOT_URI=https://via.placeholder.com/400');
        putenv('CAMERA_2_REBOOT_URI');
        putenv('CAMERA_3_NAME');

        $expectedCameraOne = new Camera(
            'foo',
            Uri::createFromString('https://via.placeholder.com/500')
        );
        $expectedCameraTwo = new Camera(
            'bar',
            Uri::createFromString('https://via.placeholder.com/400')
        );

        $this->assertEquals(
            [
                $expectedCameraOne,
                $expectedCameraTwo,
            ],
            $this->cameraFactory->createAll()
        );
    }

    /**
     * @test
     */
    public function itDoesNotCreateCameraWithoutSnapshotUri()
    {
        putenv('CAMERA_1_NAME=foo');
        putenv('CAMERA_1_SNAPSHOT_URI');

        $this->assertEmpty($this->cameraFactory->createAll());
    }

    /**
     * @test
     */
    public function itCreatesCameraWithRebootUri()
    {
        putenv('CAMERA_1_NAME=foo');
        putenv('CAMERA_1_SNAPSHOT_URI=https://via.placeholder.com/500');
        putenv('CAMERA_1_REBOOT_URI=https://example.org/reboot1');
        putenv('CAMERA_2_NAME');

        $expectedCamera = new Camera(
            'foo',
            Uri::createFromString('https://via.placeholder.com/500')
        );
        $expectedCamera->setRebootUri(
            Uri::createFromString('https://example.org/reboot1')
        );

        $this->assertEquals(
            [
                $expectedCamera,
            ],
            $this->cameraFactory->createAll()
        );
    }
}
