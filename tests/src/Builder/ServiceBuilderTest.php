<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Xylemical\Container\Definition\ServiceDefinition;
use Xylemical\Container\Definition\ServiceInterface;

/**
 * Tests \Xylemical\Container\Builder\ServiceBuilder.
 */
class ServiceBuilderTest extends TestCase {

  use ProphecyTrait;

  /**
   * Get a mock service.
   *
   * @return \Xylemical\Container\Definition\ServiceInterface
   *   The service.
   */
  protected function getMockService(): ServiceInterface {
    return $this->getMockBuilder(ServiceInterface::class)->getMock();
  }

  /**
   * Get a mock service builder.
   *
   * @param \Xylemical\Container\Definition\ServiceInterface|null $service
   *   The service.
   *
   * @return \Xylemical\Container\Builder\ServiceBuilderInterface
   *   The mock service builder.
   */
  protected function getMockServiceBuilder(?ServiceInterface $service): ServiceBuilderInterface {
    $serviceBuilder = $this->prophesize(ServiceBuilderInterface::class);
    $serviceBuilder->build(Argument::any(), Argument::any())
      ->willReturn($service);
    return $serviceBuilder->reveal();
  }

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $mockBuilder = $this->getMockBuilder(BuilderInterface::class)->getMock();

    $builder = new ServiceBuilder();
    $service = $this->getMockService();
    $s1 = $this->getMockServiceBuilder(NULL);
    $s2 = $this->getMockServiceBuilder($service);

    $definition = $this->getMockBuilder(ServiceDefinition::class)
      ->disableOriginalConstructor()
      ->getMock();
    $builder->setBuilders([$s1]);
    $this->assertNull($builder->build($definition, $mockBuilder));

    $builder->addBuilder($s2);
    $this->assertSame($service, $builder->build($definition, $mockBuilder));
  }

}
