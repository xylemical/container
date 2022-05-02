<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Xylemical\Container\Definition\ServiceInterface;
use Xylemical\Container\Exception\InvalidDefinitionException;

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
   * @param bool $applies
   *   The applies value.
   * @param \Xylemical\Container\Definition\ServiceInterface|null $service
   *   The service.
   *
   * @return \Xylemical\Container\Builder\ServiceBuilderInterface
   *   The mock service builder.
   */
  protected function getMockServiceBuilder(bool $applies, ?ServiceInterface $service): ServiceBuilderInterface {
    $serviceBuilder = $this->prophesize(ServiceBuilderInterface::class);
    $serviceBuilder->applies(Argument::any())->willReturn($applies);
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
    $s1 = $this->getMockServiceBuilder(FALSE, NULL);
    $s2 = $this->getMockServiceBuilder(TRUE, $service);

    $builder->setBuilders([$s1]);
    $this->assertFalse($builder->applies([]));

    $builder->addBuilder($s2);
    $this->assertTrue($builder->applies([]));
    $this->assertSame($service, $builder->build([], $mockBuilder));
  }

  /**
   * Test exception when build called without applies.
   */
  public function testException(): void {
    $mockBuilder = $this->getMockBuilder(BuilderInterface::class)->getMock();
    $builder = new ServiceBuilder();
    $this->expectException(InvalidDefinitionException::class);
    $builder->build([], $mockBuilder);
  }

}
