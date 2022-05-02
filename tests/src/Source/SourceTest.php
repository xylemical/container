<?php

declare(strict_types=1);

namespace Xylemical\Container\Source;

use PHPUnit\Framework\TestCase;
use Xylemical\Container\Builder\ArgumentBuilderInterface;
use Xylemical\Container\Builder\PropertyBuilderInterface;
use Xylemical\Container\Builder\ServiceBuilderInterface;
use Xylemical\Container\Definition\Definition;
use Xylemical\Container\Definition\TestDefinition;

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
    $this->assertEquals([], $source->getDefinition());
    $this->assertEquals([], $source->getServiceBuilders());
    $this->assertEquals([], $source->getArgumentBuilders());
    $this->assertEquals([], $source->getPropertyBuilders());
    $this->assertEquals($timestamp, $source->getTimestamp());

    $argumentBuilder = $this->getMockBuilder(ArgumentBuilderInterface::class)->getMock();
    $serviceBuilder = $this->getMockBuilder(ServiceBuilderInterface::class)->getMock();
    $propertyBuilder = $this->getMockBuilder(PropertyBuilderInterface::class)->getMock();

    $source
      ->setClass(TestDefinition::class)
      ->setDefinition(['test' => TRUE])
      ->setServiceBuilders([$serviceBuilder])
      ->setArgumentBuilders([$argumentBuilder])
      ->setPropertyBuilders([$propertyBuilder])
      ->setTimestamp($timestamp + 100);

    $this->assertEquals(TestDefinition::class, $source->getClass());
    $this->assertEquals(['test' => TRUE], $source->getDefinition());
    $this->assertEquals([$serviceBuilder], $source->getServiceBuilders());
    $this->assertEquals([$argumentBuilder], $source->getArgumentBuilders());
    $this->assertEquals([$propertyBuilder], $source->getPropertyBuilders());
    $this->assertEquals($timestamp + 100, $source->getTimestamp());

    $source->setTimestamp(NULL);
    $this->assertEquals(time(), $source->getTimestamp());
  }

}
