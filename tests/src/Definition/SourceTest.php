<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition;

use PHPUnit\Framework\TestCase;
use Xylemical\Container\Builder\ArgumentBuilderInterface;
use Xylemical\Container\Builder\Property\ServiceCollectorPropertyBuilder;
use Xylemical\Container\Builder\PropertyBuilderInterface;
use Xylemical\Container\Builder\ServiceBuilderInterface;

/**
 * Tests \Xylemical\Container\Source\Source.
 */
class SourceTest extends TestCase {

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $timestamp = time();

    $source = new Source();
    $this->assertEquals(Definition::class, $source->getClass());
    $this->assertEquals([], $source->getServices());
    $this->assertNull($source->getService(SourceInterface::class));
    $this->assertFalse($source->hasService(SourceInterface::class));
    $this->assertEquals([], $source->findTaggedServices('test'));
    $this->assertEquals([], $source->getServiceBuilders());
    $this->assertEquals([], $source->getArgumentBuilders());
    $this->assertEquals([], $source->getPropertyBuilders());
    $this->assertEquals($timestamp, $source->getTimestamp());

    $source->load();

    $this->assertEquals(Definition::class, $source->getClass());
    $this->assertEquals([], $source->getServices());
    $this->assertNull($source->getService(SourceInterface::class));
    $this->assertFalse($source->hasService(SourceInterface::class));
    $this->assertEquals([], $source->findTaggedServices('test'));
    $this->assertEquals([], $source->getServiceBuilders());
    $this->assertEquals([], $source->getArgumentBuilders());
    $this->assertEquals([], $source->getPropertyBuilders());
    $this->assertEquals($timestamp, $source->getTimestamp());

    $argumentBuilder = ArgumentBuilderInterface::class;
    $serviceBuilder = ServiceBuilderInterface::class;
    $propertyBuilder = PropertyBuilderInterface::class;

    $service = new ServiceDefinition(SourceInterface::class);
    $service->addTag('test');
    $source
      ->setClass(TestDefinition::class)
      ->setService($service)
      ->setServiceBuilders([$serviceBuilder])
      ->setArgumentBuilders([$argumentBuilder])
      ->setPropertyBuilders([$propertyBuilder])
      ->setTimestamp($timestamp + 100);

    $this->assertEquals(TestDefinition::class, $source->getClass());
    $this->assertEquals([SourceInterface::class => $service], $source->getServices());
    $this->assertEquals([$service], $source->findTaggedServices('test'));
    $this->assertEquals([], $source->findTaggedServices('foo'));
    $this->assertSame($service, $source->getService(SourceInterface::class));
    $this->assertTrue($source->hasService(SourceInterface::class));
    $this->assertEquals([$serviceBuilder], $source->getServiceBuilders());
    $this->assertTrue($source->hasServiceBuilder(ServiceBuilderInterface::class));
    $this->assertFalse($source->hasServiceBuilder(ArgumentBuilderInterface::class));
    $source->removeServiceBuilder(ServiceBuilderInterface::class);
    $this->assertFalse($source->hasServiceBuilder(ServiceBuilderInterface::class));
    $this->assertEquals([$argumentBuilder], $source->getArgumentBuilders());
    $this->assertTrue($source->hasArgumentBuilder(ArgumentBuilderInterface::class));
    $this->assertFalse($source->hasArgumentBuilder(ServiceBuilderInterface::class));
    $source->removeArgumentBuilder(ArgumentBuilderInterface::class);
    $this->assertFalse($source->hasArgumentBuilder(ArgumentBuilderInterface::class));
    $this->assertEquals([$propertyBuilder], $source->getPropertyBuilders());
    $this->assertTrue($source->hasPropertyBuilder(PropertyBuilderInterface::class));
    $this->assertFalse($source->hasPropertyBuilder(ServiceBuilderInterface::class));
    $source->removePropertyBuilder(PropertyBuilderInterface::class);
    $this->assertFalse($source->hasPropertyBuilder(PropertyBuilderInterface::class));

    $this->assertEquals($timestamp + 100, $source->getTimestamp());

    $source->setTimestamp(NULL);
    $this->assertEquals(time(), $source->getTimestamp());
  }

  /**
   * Tests load process.
   */
  public function testLoad(): void {
    $source = (new TestSource)->load();
    $this->assertTrue($source->hasService(SourceInterface::class));
    $this->assertTrue($source->hasPropertyBuilder(ServiceCollectorPropertyBuilder::class));
    $service = $source->getService(SourceInterface::class);
    $this->assertTrue($service->hasTag('test'));
    $this->assertTrue($service->hasTag('foo'));
    $this->assertFalse($service->hasTag('bar'));
  }

}
