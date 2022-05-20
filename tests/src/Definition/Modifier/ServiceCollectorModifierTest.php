<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition\Modifier;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Xylemical\Container\Definition\ServiceDefinition;
use Xylemical\Container\Definition\SourceInterface;

/**
 * Tests \Xylemical\Container\Definition\Modifier\ServiceCollectorModifier.
 */
class ServiceCollectorModifierTest extends TestCase {

  use ProphecyTrait;

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $s1 = (new ServiceDefinition('s1'))
      ->addTag('service.collector', [
        'tag' => 'dummy',
        'method' => 'addTag',
      ]);
    $s2 = new ServiceDefinition('s2');
    $s3 = new ServiceDefinition('s3');

    $source = $this->prophesize(SourceInterface::class);
    $source->findTaggedServices('service.collector')->willReturn([$s1]);
    $source->findTaggedServices('dummy')->willReturn([$s2, $s3]);
    $source->addPropertyBuilder(Argument::any())->will(function ($args) {
      return $this;
    });
    $source = $source->reveal();

    $modifier = new ServiceCollectorModifier();

    $modifier->apply($source);

    $this->assertTrue($s1->hasProperty('service.collector'));
    $property = $s1->getProperty('service.collector');
    $this->assertEquals('addTag', $property['method']);
    $this->assertEquals(['s2', 's3'], $property['services']);
  }

}
