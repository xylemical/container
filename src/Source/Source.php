<?php

declare(strict_types=1);

namespace Xylemical\Container\Source;

use Xylemical\Container\Definition\Definition;

/**
 * Provide a generic in-memory source.
 */
class Source implements SourceInterface {

  /**
   * The definition class.
   *
   * @var string
   */
  protected string $class = Definition::class;

  /**
   * The definition.
   *
   * @var array
   */
  protected array $definition = [];

  /**
   * The service builders.
   *
   * @var \Xylemical\Container\Builder\ServiceBuilderInterface[]
   */
  protected array $serviceBuilders = [];

  /**
   * The argument builders.
   *
   * @var \Xylemical\Container\Builder\ArgumentBuilderInterface[]
   */
  protected array $argumentBuilders = [];

  /**
   * The property builders.
   *
   * @var \Xylemical\Container\Builder\PropertyBuilderInterface[]
   */
  protected array $propertyBuilders = [];

  /**
   * The source timestamp.
   *
   * @var int
   */
  protected int $timestamp;

  /**
   * {@inheritdoc}
   */
  public function getClass(): string {
    return $this->class;
  }

  /**
   * Set the definition class.
   *
   * @param string $class
   *   The class.
   *
   * @return $this
   */
  public function setClass(string $class): static {
    $this->class = $class;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinition(): array {
    return $this->definition;
  }

  /**
   * Set the definition.
   *
   * @param array $definition
   *   The definition.
   *
   * @return $this
   */
  public function setDefinition(array $definition): static {
    $this->definition = $definition;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getServiceBuilders(): array {
    return $this->serviceBuilders;
  }

  /**
   * Set the service builders.
   *
   * @param \Xylemical\Container\Builder\ServiceBuilderInterface[] $builders
   *   The service builders.
   *
   * @return $this
   */
  public function setServiceBuilders(array $builders): static {
    $this->serviceBuilders = $builders;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getArgumentBuilders(): array {
    return $this->argumentBuilders;
  }

  /**
   * Set the argument builders.
   *
   * @param \Xylemical\Container\Builder\ArgumentBuilderInterface[] $builders
   *   The builders.
   *
   * @return $this
   */
  public function setArgumentBuilders(array $builders): static {
    $this->argumentBuilders = $builders;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPropertyBuilders(): array {
    return $this->propertyBuilders;
  }

  /**
   * Set the property builders.
   *
   * @param \Xylemical\Container\Builder\PropertyBuilderInterface[] $builders
   *   The builders.
   *
   * @return $this
   */
  public function setPropertyBuilders(array $builders): static {
    $this->propertyBuilders = $builders;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTimestamp(): int {
    if (!isset($this->timestamp)) {
      $this->timestamp = time();
    }
    return $this->timestamp;
  }

  /**
   * Set the timestamp.
   *
   * @param int|null $timestamp
   *   The timestamp or NULL. NULL sets to current time.
   *
   * @return $this
   */
  public function setTimestamp(?int $timestamp): static {
    $this->timestamp = is_null($timestamp) ? time() : $timestamp;
    return $this;
  }

}
