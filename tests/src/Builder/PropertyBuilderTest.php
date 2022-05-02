<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Xylemical\Container\Definition\PropertyInterface;
use Xylemical\Container\Definition\ServiceInterface;
use Xylemical\Container\Exception\InvalidDefinitionException;

/**
 * Tests \Xylemical\Container\Builder\PropertyBuilder.
 */
class PropertyBuilderTest extends TestCase {

  use ProphecyTrait;

  /**
   * Get a mock property.
   *
   * @return \Xylemical\Container\Definition\PropertyInterface
   *   The property.
   */
  protected function getMockProperty(): PropertyInterface {
    return $this->getMockBuilder(PropertyInterface::class)->getMock();
  }

  /**
   * Get a mock property builder.
   *
   * @param bool $applies
   *   The applies value.
   * @param \Xylemical\Container\Definition\PropertyInterface|null $property
   *   The property.
   *
   * @return \Xylemical\Container\Builder\PropertyBuilderInterface
   *   The mock property builder.
   */
  protected function getMockPropertyBuilder(bool $applies, ?PropertyInterface $property): PropertyBuilderInterface {
    $propertyBuilder = $this->prophesize(PropertyBuilderInterface::class);
    $propertyBuilder->applies(Argument::any(), Argument::any(), Argument::any())
      ->willReturn($applies);
    $propertyBuilder->build(Argument::any(), Argument::any(), Argument::any(), Argument::any())
      ->willReturn($property);
    return $propertyBuilder->reveal();
  }

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $mockBuilder = $this->getMockBuilder(BuilderInterface::class)->getMock();
    $service = $this->getMockBuilder(ServiceInterface::class)->getMock();

    $builder = new PropertyBuilder();
    $property = $this->getMockProperty();
    $s1 = $this->getMockPropertyBuilder(FALSE, NULL);
    $s2 = $this->getMockPropertyBuilder(TRUE, $property);

    $builder->setBuilders([$s1]);
    $this->assertFalse($builder->applies('test', [], $service));

    $builder->addBuilder($s2);
    $this->assertTrue($builder->applies('test', [], $service));
    $this->assertSame($property, $builder->build('test', [], $service, $mockBuilder));
  }

  /**
   * Test exception when build called without applies.
   */
  public function testException(): void {
    $service = $this->getMockBuilder(ServiceInterface::class)->getMock();
    $mockBuilder = $this->getMockBuilder(BuilderInterface::class)->getMock();
    $builder = new PropertyBuilder();
    $this->expectException(InvalidDefinitionException::class);
    $builder->build('test', [], $service, $mockBuilder);
  }

}
