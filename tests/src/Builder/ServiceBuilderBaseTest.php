<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Xylemical\Container\Definition\ArgumentInterface;
use Xylemical\Container\Definition\PropertyInterface;
use Xylemical\Container\Definition\Service;
use Xylemical\Container\Definition\ServiceDefinition;
use Xylemical\Container\Definition\ServiceInterface;

/**
 * Tests \Xylemical\Container\Builder\ServiceBuilderBase.
 */
class ServiceBuilderBaseTest extends TestCase {

  use ProphecyTrait;

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $argument = $this->getMockBuilder(ArgumentInterface::class)->getMock();
    $property = $this->prophesize(PropertyInterface::class);
    $property->getName()->willReturn('test');
    $property = $property->reveal();

    $builder = $this->prophesize(BuilderInterface::class);
    $builder->getArgument(Argument::any(), Argument::any())->willReturn($argument);
    $builder->getProperty('none', Argument::any(), Argument::any())->willReturn(NULL);
    $builder->getProperty('test', Argument::any(), Argument::any())->willReturn($property);

    $serviceBuilder = $this->getMockForAbstractClass(ServiceBuilderBase::class);

    $definition = (new ServiceDefinition(ServiceInterface::class, []))
      ->setClass(Service::class)
      ->setArguments(['dummy'])
      ->setProperty('none', 'none')
      ->setProperty('test', 'test');

    $service = $serviceBuilder->build($definition, $builder->reveal());
    $this->assertEquals(ServiceInterface::class, $service->getName());
    $this->assertEquals(Service::class, $service->getClass());
    $this->assertEquals([$argument], $service->getArguments());
    $this->assertEquals(['test' => $property], $service->getProperties());

    $definition = new ServiceDefinition(Service::class, []);
    $service = $serviceBuilder->build($definition, $builder->reveal());
    $this->assertEquals(Service::class, $service->getName());
    $this->assertEquals(Service::class, $service->getClass());
  }

}
