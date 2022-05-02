<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder;

use Xylemical\Container\Definition\PropertyInterface;
use Xylemical\Container\Definition\ServiceInterface;
use Xylemical\Container\Exception\InvalidDefinitionException;

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
  public function applies(string $name, mixed $property, ServiceInterface $service): bool {
    foreach ($this->builders as $builder) {
      if ($builder->applies($name, $property, $service)) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function build(string $name, mixed $property, ServiceInterface $service, BuilderInterface $builder): PropertyInterface {
    foreach ($this->builders as $prop) {
      if ($prop->applies($name, $property, $service)) {
        return $prop->build($name, $property, $service, $builder);
      }
    }
    throw new InvalidDefinitionException();
  }

}
