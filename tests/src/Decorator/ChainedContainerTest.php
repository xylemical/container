<?php

declare(strict_types=1);

namespace Xylemical\Container\Decorator;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use Xylemical\Container\ContainerDecoratedInterface;

/**
 * Tests \Xylemical\Container\Decorator\ChainedContainer.
 */
class ChainedContainerTest extends TestCase {

  use ProphecyTrait;

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $container = $this->prophesize(ContainerInterface::class);
    $container->has(Argument::any())->willReturn(TRUE);
    $container->get(Argument::any())->willReturn(FALSE);
    $container->get('bar')->willReturn(TRUE);

    $primary = $this->prophesize(ContainerInterface::class);
    $primary->has('foo')->willReturn(TRUE);
    $primary->get('foo')->willReturn(FALSE);
    $primary->has(Argument::any())->willReturn(FALSE);

    $decorated = new ChainedContainer($container->reveal(), $primary->reveal());
    $this->assertTrue($decorated->has('foo'));
    $this->assertFalse($decorated->get('foo'));
    $this->assertTrue($decorated->has('bar'));
    $this->assertTrue($decorated->get('bar'));
    $this->assertTrue($decorated->has('baz'));
    $this->assertFalse($decorated->get('baz'));
  }

  /**
   * Test decorator behaviour.
   */
  public function testRoot(): void {
    $root = $this->prophesize(ContainerInterface::class);
    $root = $root->reveal();

    $container = $this->prophesize(ContainerDecoratedInterface::class);
    $container->setRoot(Argument::any())->will(function () {
      return $this;
    })->shouldBeCalledTimes(2);

    $primary = $this->prophesize(ContainerDecoratedInterface::class);
    $primary->setRoot(Argument::any())->will(function () {
      return $this;
    })->shouldBeCalledTimes(2);

    $instance = new ChainedContainer($container->reveal(), $primary->reveal());
    $instance->setRoot($root);
  }

}
