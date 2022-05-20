<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition;

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
   * The source timestamp.
   *
   * @var int
   */
  protected int $timestamp;

  /**
   * {@inheritdoc}
   */
  public function load(): static {
    $source = $this->doLoad();

    $this->class = $source['class'] ?? $this->class ?? Definition::class;
    $this->serviceBuilders = $source['service_builders'] ?? [];
    $this->argumentBuilders = $source['argument_builders'] ?? [];
    $this->propertyBuilders = $source['property_builders'] ?? [];
    $this->services = [];
    foreach ($source['services'] ?? [] as $name => $definition) {
      $this->services[$name] = new ServiceDefinition($name, $definition);
    }

    $modifiers = new Modifier();
    foreach ($source['modifiers'] ?? [] as $modifier) {
      $modifiers->addModifier(new $modifier());
    }

    $modifiers->apply($this);

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
  public function setClass(string $class): static {
    $this->class = $class;
    return $this;
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
  public function getService(string $class): ?ServiceDefinition {
    return $this->services[$class] ?? NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function hasService(string $class): bool {
    return isset($this->services[$class]);
  }

  /**
   * {@inheritdoc}
   */
  public function setService(ServiceDefinition $definition): static {
    $this->services[$definition->getName()] = $definition;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function findTaggedServices(string $tag): array {
    $services = [];
    foreach ($this->services as $service) {
      if ($service->hasTag($tag)) {
        $services[] = $service;
      }
    }
    return $services;
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
  public function setServiceBuilders(array $builders): static {
    $this->serviceBuilders = [];
    foreach ($builders as $builder) {
      $this->addServiceBuilder($builder);
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function hasServiceBuilder(string $class): bool {
    return in_array($class, $this->serviceBuilders);
  }

  /**
   * {@inheritdoc}
   */
  public function addServiceBuilder(string $class): static {
    if (!in_array($class, $this->serviceBuilders)) {
      $this->serviceBuilders[] = $class;
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function removeServiceBuilder(string $class): static {
    $this->serviceBuilders = array_filter(
      $this->serviceBuilders,
      function ($service) use ($class) {
        return $service !== $class;
      }
    );
    return $this;
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
  public function setArgumentBuilders(array $builders): static {
    $this->argumentBuilders = [];
    foreach ($builders as $builder) {
      $this->addArgumentBuilder($builder);
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function hasArgumentBuilder(string $class): bool {
    return in_array($class, $this->argumentBuilders);
  }

  /**
   * {@inheritdoc}
   */
  public function addArgumentBuilder(string $class): static {
    if (!in_array($class, $this->argumentBuilders)) {
      $this->argumentBuilders[] = $class;
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function removeArgumentBuilder(string $class): static {
    $this->argumentBuilders = array_filter(
      $this->argumentBuilders,
      function ($argument) use ($class) {
        return $argument !== $class;
      }
    );
    return $this;
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
  public function setPropertyBuilders(array $builders): static {
    $this->propertyBuilders = [];
    foreach ($builders as $builder) {
      $this->addPropertyBuilder($builder);
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function hasPropertyBuilder(string $class): bool {
    return in_array($class, $this->propertyBuilders);
  }

  /**
   * {@inheritdoc}
   */
  public function addPropertyBuilder(string $class): static {
    if (!in_array($class, $this->propertyBuilders)) {
      $this->propertyBuilders[] = $class;
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function removePropertyBuilder(string $class): static {
    $this->propertyBuilders = array_filter(
      $this->propertyBuilders,
      function ($property) use ($class) {
        return $property !== $class;
      }
    );
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
   * Sets the timestamp.
   *
   * @param int|null $timestamp
   *   The timestamp.
   *
   * @return $this
   */
  public function setTimestamp(?int $timestamp): static {
    if (is_null($timestamp)) {
      unset($this->timestamp);
    }
    else {
      $this->timestamp = $timestamp;
    }
    return $this;
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
