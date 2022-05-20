<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Xylemical\Container\Container;
use Xylemical\Container\Definition\Argument\EnvironmentArgument;
use Xylemical\Container\Definition\Argument\ServiceArgument;
use Xylemical\Container\Definition\Argument\ValueArgument;
use Xylemical\Container\Definition\ArgumentInterface;
use Xylemical\Container\Definition\Definition;
use Xylemical\Container\Definition\PropertyInterface;
use Xylemical\Container\Definition\Service;
use Xylemical\Container\Definition\ServiceDefinition;
use Xylemical\Container\Definition\ServiceInterface;
use Xylemical\Container\Definition\SourceInterface;
use Xylemical\Container\Definition\TestDefinition;
use Xylemical\Container\Definition\TestProperty;
use Xylemical\Container\Definition\TestService;
use Xylemical\Container\Exception\InvalidDefinitionException;
use Xylemical\Container\TestContainer;

/**
 * Tests \Xylemical\Container\Builder\Builder.
 */
class BuilderTest extends TestCase {

  use ProphecyTrait;

  /**
   * Get a mock argument builder.
   *
   * @param mixed $definition
   *   The definition.
   * @param \Xylemical\Container\Definition\ArgumentInterface|null $argument
   *   The return argument.
   *
   * @return \Xylemical\Container\Builder\ArgumentBuilderInterface
   *   The mocked builder.
   */
  protected function getMockArgumentBuilder(mixed $definition, ?ArgumentInterface $argument): ArgumentBuilderInterface {
    $builder = $this->prophesize(ArgumentBuilderInterface::class);
    $builder->applies($definition, Argument::any())->willReturn(TRUE);
    $builder->build($definition, Argument::any(), Argument::any())
      ->willReturn($argument);
    $builder->applies(Argument::any(), Argument::any())->willReturn(FALSE);
    return $builder->reveal();
  }

  /**
   * Get a mock property builder.
   *
   * @param mixed $definition
   *   The definition.
   * @param \Xylemical\Container\Definition\PropertyInterface|null $property
   *   The return property.
   *
   * @return \Xylemical\Container\Builder\PropertyBuilderInterface
   *   The mocked property builder.
   */
  protected function getMockPropertyBuilder(mixed $definition, ?PropertyInterface $property): PropertyBuilderInterface {
    $builder = $this->prophesize(PropertyBuilderInterface::class);
    $builder->applies(Argument::any(), $definition, Argument::any())
      ->willReturn(TRUE);
    $builder->build(Argument::any(), $definition, Argument::any(), Argument::any())
      ->willReturn($property);
    $builder->applies(Argument::any(), Argument::any(), Argument::any())
      ->willReturn(FALSE);
    return $builder->reveal();
  }

  /**
   * Create a mock source.
   *
   * @param bool $custom
   *   The provides custom behaviours.
   *
   * @return \Xylemical\Container\Definition\SourceInterface
   *   The mocked source.
   */
  protected function getMockSource(bool $custom = FALSE): SourceInterface {
    $definition = (new ServiceDefinition(Service::class))
      ->setArguments([
        '%OMG:default%',
        '%FOO%',
        '@source',
        10,
        'something shocking',
      ])
      ->setProperty('test', 'test')
      ->setProperty('none', 'none');

    $source = $this->prophesize(SourceInterface::class);
    $source->getClass()
      ->willReturn($custom ? TestDefinition::class : Definition::class);
    $source->getServices()->willReturn([$definition]);
    if ($custom) {
      $source->getServiceBuilders()->willReturn([TestServiceBuilder::class]);
      $source->getArgumentBuilders()->willReturn([TestArgumentBuilder::class]);
      $source->getPropertyBuilders()->willReturn([TestPropertyBuilder::class]);
    }
    else {
      $source->getServiceBuilders()->willReturn([]);
      $source->getArgumentBuilders()->willReturn([]);
      $source->getPropertyBuilders()->willReturn([]);
    }
    return $source->reveal();
  }

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $service = $this->getMockBuilder(ServiceInterface::class)->getMock();

    $definition = new ServiceDefinition('test');
    $source = $this->getMockSource();
    $builder = new Builder(Container::class, $source);
    $this->assertNull($builder->getService($definition));
    $this->assertNull($builder->getProperty('test', $service, 'null'));
    $this->assertInstanceOf(ValueArgument::class, $builder->getArgument($service, NULL));

    $definition = $builder->getDefinition();
    $this->assertInstanceOf(Definition::class, $definition);
    $this->assertEquals(Container::class, $definition->getClass());
    $this->assertNull($definition->getService(ServiceInterface::class));
    $service = $definition->getService(Service::class);
    $this->assertNotNull($service);
    $this->assertEquals(Service::class, $service->getName());
    $this->assertEquals(Service::class, $service->getClass());
    $this->assertEquals(5, count($service->getArguments()));
    $this->assertInstanceOf(EnvironmentArgument::class, $service->getArgument(0));
    $this->assertInstanceOf(EnvironmentArgument::class, $service->getArgument(1));
    $this->assertInstanceOf(ServiceArgument::class, $service->getArgument(2));
    $this->assertInstanceOf(ValueArgument::class, $service->getArgument(3));
    $this->assertInstanceOf(ValueArgument::class, $service->getArgument(4));
    $this->assertEquals(0, count($service->getProperties()));
  }

  /**
   * Tests source builder overrides.
   */
  public function testSourceBuilders(): void {
    $source = $this->getMockSource(TRUE);
    $builder = new Builder(TestContainer::class, $source);

    $definition = $builder->getDefinition();
    $this->assertEquals(TestContainer::class, $definition->getClass());
    $this->assertInstanceOf(TestDefinition::class, $definition);
    $this->assertNull($definition->getService(ServiceInterface::class));
    $service = $definition->getService(Service::class);
    $this->assertNotNull($service);
    $this->assertInstanceOf(TestService::class, $service);
    $this->assertEquals(Service::class, $service->getName());
    $this->assertEquals(Service::class, $service->getClass());
    $this->assertEquals(5, count($service->getArguments()));
    $this->assertInstanceOf(EnvironmentArgument::class, $service->getArgument(0));
    $this->assertInstanceOf(EnvironmentArgument::class, $service->getArgument(1));
    $this->assertInstanceOf(ServiceArgument::class, $service->getArgument(2));
    $this->assertInstanceOf(ServiceArgument::class, $service->getArgument(3));
    $this->assertInstanceOf(ValueArgument::class, $service->getArgument(4));
    $this->assertEquals(1, count($service->getProperties()));
    $this->assertTrue($service->hasProperty('property'));
    $this->assertInstanceOf(TestProperty::class, $service->getProperty('property'));
  }

  /**
   * Tests that an invalid source definition class raises an exception.
   */
  public function testInvalidDefinition(): void {
    $source = $this->prophesize(SourceInterface::class);
    $source->getClass()->willReturn(Service::class);
    $source->getServices()->willReturn([]);
    $source->getServiceBuilders()->willReturn([]);
    $source->getArgumentBuilders()->willReturn([]);
    $source->getPropertyBuilders()->willReturn([]);

    $builder = new Builder(Container::class, $source->reveal());

    $this->expectException(InvalidDefinitionException::class);
    $builder->getDefinition();
  }

}
