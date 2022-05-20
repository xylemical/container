<?php

declare(strict_types=1);

namespace Xylemical\Container\Compiler;

use PHPUnit\Framework\TestCase;
use Xylemical\Container\Definition\Argument\ServiceArgument;
use Xylemical\Container\Definition\Definition;
use Xylemical\Container\Definition\Service;
use Xylemical\Container\Definition\ServiceInterface;
use Xylemical\Container\Definition\Source;
use Xylemical\Container\Definition\SourceInterface;
use Xylemical\Container\Exception\CyclicDefinitionException;

/**
 * Tests \Xylemical\Container\Compiler.
 */
class CompilerTest extends TestCase {

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $definition = new Definition('\\App\\Container');

    $compiler = new Compiler();

    $contents = file_get_contents(__DIR__ . '/../../fixtures/TestSanity.php');
    $this->assertEquals($contents, $compiler->compile($definition));
  }

  /**
   * Tests compilation.
   */
  public function testCompilation(): void {
    $definition = new Definition('\\App\\Container');
    $definition->addServices([
      (new Service(ServiceInterface::class, Service::class))
        ->addArgument(new ServiceArgument(SourceInterface::class)),
      (new Service(SourceInterface::class, Source::class)),
    ]);

    $compiler = new Compiler();

    $contents = file_get_contents(__DIR__ . '/../../fixtures/TestCompilation.php');
    $this->assertEquals($contents, $compiler->compile($definition));
  }

  /**
   * Test cyclic dependencies.
   */
  public function testCyclicDependencies(): void {
    $definition = new Definition('\\App\\Container');
    $definition->addServices([
      (new Service(ServiceInterface::class, Service::class))
        ->addArgument(new ServiceArgument(SourceInterface::class)),
      (new Service(SourceInterface::class, SourceInterface::class))
        ->addArgument(new ServiceArgument(ServiceInterface::class)),
    ]);

    $this->expectException(CyclicDefinitionException::class);
    $compiler = new Compiler();
    $compiler->compile($definition);
  }

}
