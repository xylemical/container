<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition\Source;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Xylemical\Container\Definition\Definition;
use Xylemical\Container\Definition\ServiceDefinition;
use Xylemical\Container\Definition\SourceInterface;

/**
 * Tests \Xylemical\Container\Definition\Source\ChainedSource.
 */
class ChainedSourceTest extends TestCase {

  use ProphecyTrait;

  /**
   * Create a mock source.
   *
   * @param array $definition
   *   The definition.
   *
   * @return \Xylemical\Container\Definition\SourceInterface
   *   The source.
   */
  protected function getMockSource(array $definition): SourceInterface {
    $source = $this->prophesize(SourceInterface::class);
    $source->getClass()->willReturn($definition['class'] ?? Definition::class);
    $source->getTimestamp()->willReturn($definition['timestamp'] ?? 0);
    $source->getServices()->willReturn($definition['services'] ?? []);
    $source->getServiceBuilders()->willReturn($definition['service_builders'] ?? []);
    $source->getArgumentBuilders()->willReturn($definition['argument_builders'] ?? []);
    $source->getPropertyBuilders()->willReturn($definition['property_builders'] ?? []);
    $source->getModifiers()->willReturn($definition['modifiers'] ?? []);
    $source->load()->will(function () {
      return $this;
    });
    return $source->reveal();
  }

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $time = time();
    $first = $this->getMockSource([
      'class' => 'a',
      'timestamp' => $time + 5,
      'services' => ['a' => new ServiceDefinition('a')],
      'service_builders' => ['a', 'c'],
      'argument_builders' => ['a', 'c'],
      'property_builders' => ['a', 'c'],
      'modifiers' => ['a', 'c'],
    ]);
    $second = $this->getMockSource([
      'class' => 'b',
      'timestamp' => $time + 10,
      'services' => [
        'a' => new ServiceDefinition('b'),
        'b' => new ServiceDefinition('b'),
      ],
      'service_builders' => ['a', 'b'],
      'argument_builders' => ['a', 'b'],
      'property_builders' => ['a', 'b'],
      'modifiers' => ['a', 'b'],
    ]);

    $source = new ChainedSource([$first, $second]);
    $source->load();
    $this->assertEquals('a', $source->getClass());
    $this->assertEquals($time + 10, $source->getTimestamp());
    $this->assertEquals([
      'a' => new ServiceDefinition('a'),
      'b' => new ServiceDefinition('b'),
    ], $source->getServices());
    $this->assertEquals(['a', 'c', 'b'], $source->getServiceBuilders());
    $this->assertEquals(['a', 'c', 'b'], $source->getArgumentBuilders());
    $this->assertEquals(['a', 'c', 'b'], $source->getPropertyBuilders());
    $this->assertEquals(['a', 'c', 'b'], $source->getModifiers());
  }

}
