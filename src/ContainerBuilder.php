<?php

declare(strict_types=1);

namespace Xylemical\Container;

use Xylemical\Container\Builder\Builder;
use Xylemical\Container\Compiler\Compiler;
use Xylemical\Container\Definition\SourceInterface;
use Xylemical\Container\Exception\ContainerException;

/**
 * Provides the builder for the container.
 */
class ContainerBuilder {

  /**
   * The filename for the compiled container.
   *
   * @var string
   */
  protected string $filename;

  /**
   * The classname used for the compiled container.
   *
   * @var string
   */
  protected string $class;

  /**
   * The container source.
   *
   * @var \Xylemical\Container\Definition\SourceInterface
   */
  protected SourceInterface $source;

  /**
   * ContainerBuilder constructor.
   *
   * @param \Xylemical\Container\Definition\SourceInterface $source
   *   The source.
   * @param string $filename
   *   The filename to store the compiled container.
   * @param string $class
   *   The classname used for the compiled container.
   */
  public function __construct(SourceInterface $source, string $filename, string $class = 'Xylemical\\Container\\CompiledContainer') {
    $this->source = $source;
    $this->filename = $filename;
    $this->class = $class;
  }

  /**
   * Get the container from the builder.
   *
   * @return \Xylemical\Container\Container
   *   The container.
   *
   * @throws \Xylemical\Container\Exception\ContainerException
   * @throws \Xylemical\Container\Exception\CyclicDefinitionException
   * @throws \Xylemical\Container\Exception\InvalidDefinitionException
   */
  public function getContainer(): Container {
    if (!file_exists($this->filename) ||
      ($this->source->getTimestamp() > filemtime($this->filename))) {
      $this->doBuildContainer();
    }
    if (!class_exists($this->class, FALSE)) {
      include $this->filename;
    }
    return new ($this->class)();
  }

  /**
   * Build the container.
   *
   * @throws \Xylemical\Container\Exception\CyclicDefinitionException
   * @throws \Xylemical\Container\Exception\ContainerException
   * @throws \Xylemical\Container\Exception\InvalidDefinitionException
   */
  protected function doBuildContainer(): void {
    $compiler = new Compiler();
    $builder = new Builder($this->class, $this->source);
    $contents = $compiler->compile($builder->getDefinition());
    if (!@file_put_contents($this->filename, $contents)) {
      throw new ContainerException("Unable to write to container file {$this->filename}.");
    }
  }

}
