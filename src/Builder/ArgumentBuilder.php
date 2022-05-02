<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder;

use Xylemical\Container\Definition\ArgumentInterface;
use Xylemical\Container\Definition\ServiceInterface;
use Xylemical\Container\Exception\InvalidDefinitionException;

/**
 * Provides a generic argument builder.
 */
class ArgumentBuilder extends ArgumentBuilderBase {

  /**
   * The argument builders.
   *
   * @var \Xylemical\Container\Builder\ArgumentBuilderInterface[]
   */
  protected array $builders = [];

  /**
   * Set the builders.
   *
   * @param \Xylemical\Container\Builder\ArgumentBuilderInterface[] $builders
   *   The builders.
   *
   * @return $this
   */
  public function setBuilders(array $builders): static {
    $this->builders = [];
    $this->addBuilders($builders);
    return $this;
  }

  /**
   * Add argument builders.
   *
   * @param \Xylemical\Container\Builder\ArgumentBuilderInterface[] $builders
   *   The builders.
   *
   * @return $this
   */
  public function addBuilders(array $builders): static {
    foreach ($builders as $builder) {
      $this->addBuilder($builder);
    }
    return $this;
  }

  /**
   * Add an argument builder.
   *
   * @param \Xylemical\Container\Builder\ArgumentBuilderInterface $builder
   *   The builder.
   *
   * @return $this
   */
  public function addBuilder(ArgumentBuilderInterface $builder): static {
    $this->builders[] = $builder;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function applies(mixed $argument, ServiceInterface $service): bool {
    foreach ($this->builders as $builder) {
      if ($builder->applies($argument, $service)) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function build(mixed $argument, ServiceInterface $service, BuilderInterface $builder): ArgumentInterface {
    foreach ($this->builders as $arg) {
      if ($arg->applies($argument, $service)) {
        return $arg->build($argument, $service, $builder);
      }
    }
    throw new InvalidDefinitionException();
  }

}
