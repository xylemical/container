<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder;

use Xylemical\Container\Definition\PropertyInterface;
use Xylemical\Container\Definition\ServiceInterface;

/**
 * Provides a generic property builder.
 */
class PropertyBuilder extends PropertyBuilderBase {

  /**
   * The property builders.
   *
   * @var \Xylemical\Container\Builder\PropertyBuilderInterface[]
   */
  protected array $builders = [];

  /**
   * Set the builders.
   *
   * @param \Xylemical\Container\Builder\PropertyBuilderInterface[] $builders
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
   * Add property builders.
   *
   * @param \Xylemical\Container\Builder\PropertyBuilderInterface[] $builders
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
   * Add a property builder.
   *
   * @param \Xylemical\Container\Builder\PropertyBuilderInterface $builder
   *   The builder.
   *
   * @return $this
   */
  public function addBuilder(PropertyBuilderInterface $builder): static {
    $this->builders[] = $builder;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function build(string $name, mixed $property, ServiceInterface $service, BuilderInterface $builder): ?PropertyInterface {
    foreach ($this->builders as $prop) {
      if ($result = $prop->build($name, $property, $service, $builder)) {
        return $result;
      }
    }
    return NULL;
  }

}
