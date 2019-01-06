<?php

namespace Detroit\Cctv\Tests\Integration;

use Detroit\Cctv\Application\Camera\CameraServiceProvider;
use Detroit\Cctv\Infrastructure\CommandBusServiceProvider;
use Detroit\Cctv\Infrastructure\FilesystemServiceProvider;
use Detroit\Cctv\Infrastructure\TwigServiceProvider;
use League\Container\Container;
use League\Container\ReflectionContainer;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class ContainerTest extends TestCase
{
    /**
     * @var Container
     */
    private $container;

    public function setUp()
    {
        $this->container = new Container();
        $this->container->delegate(new ReflectionContainer());

        $this->container->addServiceProvider(CameraServiceProvider::class);
        $this->container->addServiceProvider(CommandBusServiceProvider::class);
        $this->container->addServiceProvider(FilesystemServiceProvider::class);
        $this->container->addServiceProvider(TwigServiceProvider::class);
    }

    /**
     * @test
     */
    public function itReturnsInstanceOfSharedDependency()
    {
        $this->markTestIncomplete();
    }

    private function getPrivatePropertyValue(
        object $instance,
        string $propertyName
    ) {
        $reflection = new ReflectionClass(get_class($instance));
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($instance);
    }
}
