<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Xylemical\Container\Container;

/**
 * Tests \Xylemical\Container\Definition\Definition.
 */
class DefinitionTest extends TestCase {

  use ProphecyTrait;

  /**
   * Get mocked service.
   *
   * @param string $name
   *   The service name.
   *
   * @return \Xylemical\Container\Definition\ServiceInterface
   *   The service.
   */
  protected function getMockService(string $name): ServiceInterface {
    $service = $this->prophesize(ServiceInterface::class);
    $service->getName()->willReturn($name);
    return $service->reveal();
  }

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $s1 = $this->getMockService('s1');
    $s2 = $this->getMockService('s2');
    $s3 = $this->getMockService('s3');
    $definition = new Definition(Container::class, [$s1, $s2, $s3]);
    $this->assertEquals(Container::class, $definition->getClass());
    $this->assertEquals([
      's1' => $s1,
      's2' => $s2,
      's3' => $s3,
    ], $definition->getServices());
    $this->assertSame($s1, $definition->getService('s1'));
    $this->assertNull($definition->getService('s0'));

    $definition->addService($s1);
    $this->assertEquals([
      's1' => $s1,
      's2' => $s2,
      's3' => $s3,
    ], $definition->getServices());

    $s4 = $this->getMockService('s4');
    $definition->setServices([$s4]);
    $this->assertEquals(['s4' => $s4], $definition->getServices());

    $definition->removeService('s4');
    $this->assertEquals([], $definition->getServices());
  }

}
