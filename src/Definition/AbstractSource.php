<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition;

use function array_unique;
use function time;

/**
 * Provides a base source.
 */
class AbstractSource implements SourceInterface {

  /**
   * The definition class.
   *
   * @var string
   */
  protected string $class = Definition::class;

  /**
   * The service definitions.
   *
   * @var \Xylemical\Container\Definition\ServiceDefinition[]
   */
  protected array $services = [];

  /**
   * The service builders.
   *
   * @var string[]
   */
  protected array $serviceBuilders = [];

  /**
   * The argument builders.
   *
   * @var string[]
   */
  protected array $argumentBuilders = [];

  /**
   * The property builders.
   *
   * @var string[]
   */
  protected array $propertyBuilders = [];

  /**
   * The source modifiers.
   *
   * @var string[]
   */
  protected array $modifiers = [];

  /**
   * The source timestamp.
   *
   * @var int
   */
  protected int $timestamp = 0;

  /**
   * {@inheritdoc}
   */
  public function load(): static {
    $source = $this->doLoad();

    $this->class = $source['class'] ?? $this->class ?? Definition::class;
    $this->serviceBuilders = array_unique($source['service_builders'] ?? []);
    $this->argumentBuilders = array_unique($source['argument_builders'] ?? []);
    $this->propertyBuilders = array_unique($source['property_builders'] ?? []);
    $this->services = [];
    foreach ($source['services'] ?? [] as $name => $definition) {
      $this->services[$name] = new ServiceDefinition($name, $definition);
    }
    $this->modifiers = array_unique($source['modifiers'] ?? []);

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getClass(): string {
    return $this->class;
  }

  /**
   * {@inheritdoc}
   */
  public function getServices(): array {
    return $this->services;
  }

  /**
   * {@inheritdoc}
   */
  public function getServiceBuilders(): array {
    return $this->serviceBuilders;
  }

  /**
   * {@inheritdoc}
   */
  public function getArgumentBuilders(): array {
    return $this->argumentBuilders;
  }

  /**
   * {@inheritdoc}
   */
  public function getPropertyBuilders(): array {
    return $this->propertyBuilders;
  }

  /**
   * {@inheritdoc}
   */
  public function getModifiers(): array {
    return $this->modifiers;
  }

  /**
   * {@inheritdoc}
   */
  public function getTimestamp(): int {
    if (!$this->timestamp) {
      $this->timestamp = time();
    }
    return $this->timestamp;
  }

  /**
   * Loads the source definition.
   *
   * @return array
   *   The definition.
   */
  protected function doLoad(): array {
    return [];
  }

}
