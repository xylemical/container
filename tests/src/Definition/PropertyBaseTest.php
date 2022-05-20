<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition;

use PHPUnit\Framework\TestCase;

/**
 * Tests \Xylemical\Container\Definition\PropertyBase.
 */
class PropertyBaseTest extends TestCase {

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $property = $this->getMockForAbstractClass(PropertyBase::class, ['test']);
    $this->assertEquals('test', $property->getName());
    $this->assertEquals([], $property->getDependencies());
  }

}
