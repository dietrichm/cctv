<?php

namespace Detroit\Cctv\Tests\Integration;

use Detroit\Cctv\Application\App;
use League\Container\Container;
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
        $this->container = (new App())->getContainer();
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
