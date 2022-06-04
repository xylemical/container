<?php

declare(strict_types=1);

namespace Xylemical\Container\Decorator;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;

/**
 * Tests \Xylemical\Container\Decorator\CallableContainer.
 */
class CallableContainerTest extends TestCase {

  use ProphecyTrait;

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $object = new \stdClass();

    $container = $this->prophesize(ContainerInterface::class);
    $container->get('test')->willReturn(TRUE);
    $container->get('foo')->willReturn(FALSE);
    $container->has('test')->willReturn(TRUE);
    $container->has('foo')->willReturn(TRUE);
    $container->has(Argument::any())->willReturn(FALSE);

    $instance = new CallableContainer($container->reveal(), [
      'foo' => function () use ($object) {
        return $object;
      },
    ]);

    $this->assertTrue($instance->has('test'));
    $this->assertTrue($instance->get('test'));
    $this->assertTrue($instance->has('foo'));
    $this->assertSame($object, $instance->get('foo'));
    $this->assertFalse($instance->has('bar'));
  }

}