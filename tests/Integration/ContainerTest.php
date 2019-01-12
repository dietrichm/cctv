<?php

namespace Detroit\Cctv\Tests\Integration;

use Detroit\Cctv\Application\Camera\CameraServiceProvider;
use Detroit\Cctv\Infrastructure\CommandBusServiceProvider;
use Detroit\Cctv\Infrastructure\FilesystemServiceProvider;
use Detroit\Cctv\Infrastructure\LoggingServiceProvider;
use Detroit\Cctv\Infrastructure\TwigServiceProvider;
use Jenssegers\Lean\SlimServiceProvider;
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
        $this->container->addServiceProvider(LoggingServiceProvider::class);
        $this->container->addServiceProvider(SlimServiceProvider::class);
        $this->container->addServiceProvider(TwigServiceProvider::class);
    }

    /**
     * @test
     */
    public function itReturnsInstanceOfSharedDependency()
    {
        foreach ($this->getSharedDependencies() as $dependency) {
            $instance = $this->container->get($dependency);
            $this->addToAssertionCount(1);

            if (interface_exists($dependency) || class_exists($dependency)) {
                $this->assertInstanceOf($dependency, $instance);
            }
        }
    }

    private function getSharedDependencies(): array
    {
        $dependencies = [];
        $serviceProviderAggregate = $this->getPrivatePropertyValue(
            $this->container,
            'providers'
        );

        foreach ($serviceProviderAggregate as $serviceProvider) {
            $dependencies = array_merge(
                $dependencies,
                $this->getPrivatePropertyValue(
                    $serviceProvider,
                    'provides'
                )
            );
        }

        return $dependencies;
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
