<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition;

use PHPUnit\Framework\TestCase;

/**
 * Tests \Xylemical\Container\Definition\ServiceDefinition.
 */
class ServiceDefinitionTest extends TestCase {

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $service = new ServiceDefinition('able', [
      'class' => 'willing',
      'arguments' => ['@service'],
      'tags' => ['crazy'],
      'outcome' => 'finished',
    ]);
    $this->assertEquals('able', $service->getName());
    $this->assertEquals('willing', $service->getClass());
    $this->assertEquals(['@service'], $service->getArguments());
    $this->assertEquals(['outcome' => 'finished'], $service->getProperties());
    $this->assertTrue($service->hasProperty('outcome'));
    $this->assertFalse($service->hasProperty('class'));
    $this->assertFalse($service->hasProperty('transfer'));
    $this->assertEquals([['name' => 'crazy']], $service->getTags());
    $this->assertEquals(['name' => 'crazy'], $service->getTag('crazy'));
    $this->assertTrue($service->hasTag('crazy'));
    $this->assertNull($service->getTag('foo'));
    $this->assertFalse($service->hasTag('foo'));

    $service->setProperties([
      'class' => 'override',
      'outcome' => 'started',
      'transfer' => 'organism',
    ]);
    $this->assertEquals([
      'outcome' => 'started',
      'transfer' => 'organism',
    ],
      $service->getProperties(),
    );
    $this->assertTrue($service->hasProperty('transfer'));

    $this->assertEquals([
      'class' => 'willing',
      'arguments' => ['@service'],
      'tags' => [['name' => 'crazy']],
      'outcome' => 'started',
      'transfer' => 'organism',
    ], $service->getDefinition());
  }

}
