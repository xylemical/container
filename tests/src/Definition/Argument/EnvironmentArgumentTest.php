<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition\Argument;

use PHPUnit\Framework\TestCase;

/**
 * Tests \Xylemical\Container\Definition\Argument\EnvironmentArgument.
 */
class EnvironmentArgumentTest extends TestCase {

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $arg = new EnvironmentArgument('OMG');
    $this->assertEquals([], $arg->getDependencies());
    $this->assertEquals("getenv('OMG')", $arg->compile());

    $arg = new EnvironmentArgument('OMG', 'foo');
    $this->assertEquals("getenv('OMG') !== FALSE ? getenv('OMG') : 'foo'", $arg->compile());
  }

}
