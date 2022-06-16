<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder\Property;

use PHPUnit\Framework\TestCase;
use Xylemical\Container\Builder\BuilderInterface;
use Xylemical\Container\Definition\PropertyInterface;
use Xylemical\Container\Definition\ServiceInterface;

/**
 * Tests \Xylemical\Container\Builder\Property\ServiceCollectorPropertyBuilder.
 */
class ServiceCollectorPropertyBuilderTest extends TestCase {

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $service = $this->getMockBuilder(ServiceInterface::class)->getMock();

    $builder = new ServiceCollectorPropertyBuilder();

    $property = $builder->build(
      '',
      '',
      $service,
      $this->getMockBuilder(BuilderInterface::class)->getMock()
    );
    $this->assertNull($property);

    $property = $builder->build(
      'service.collector',
      ['method' => 'addService', 'services' => []],
      $service,
      $this->getMockBuilder(BuilderInterface::class)->getMock()
    );
    $this->assertInstanceOf(PropertyInterface::class, $property);
  }

}
