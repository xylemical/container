<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Xylemical\Container\Definition\ArgumentInterface;
use Xylemical\Container\Definition\ServiceInterface;

/**
 * Tests \Xylemical\Container\Builder\ArgumentBuilder.
 */
class ArgumentBuilderTest extends TestCase {

  use ProphecyTrait;

  /**
   * Get a mock argument.
   *
   * @return \Xylemical\Container\Definition\ArgumentInterface
   *   The argument.
   */
  protected function getMockArgument(): ArgumentInterface {
    return $this->getMockBuilder(ArgumentInterface::class)->getMock();
  }

  /**
   * Get a mock argument builder.
   *
   * @param \Xylemical\Container\Definition\ArgumentInterface|null $argument
   *   The argument.
   *
   * @return \Xylemical\Container\Builder\ArgumentBuilderInterface
   *   The mock argument builder.
   */
  protected function getMockArgumentBuilder(?ArgumentInterface $argument): ArgumentBuilderInterface {
    $argumentBuilder = $this->prophesize(ArgumentBuilderInterface::class);
    $argumentBuilder->build(Argument::any(), Argument::any(), Argument::any())
      ->willReturn($argument);
    return $argumentBuilder->reveal();
  }

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $mockBuilder = $this->getMockBuilder(BuilderInterface::class)->getMock();
    $service = $this->getMockBuilder(ServiceInterface::class)->getMock();

    $builder = new ArgumentBuilder();
    $argument = $this->getMockArgument();
    $s1 = $this->getMockArgumentBuilder(NULL);
    $s2 = $this->getMockArgumentBuilder($argument);

    $builder->setBuilders([$s1]);
    $this->assertNull($builder->build([], $service, $mockBuilder));

    $builder->addBuilder($s2);
    $this->assertSame($argument, $builder->build([], $service, $mockBuilder));
  }

}
