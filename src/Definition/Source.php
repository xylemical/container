<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition;

/**
 * Provide a generic in-memory source.
 */
class Source extends AbstractSource {

  /**
   * Source constructor.
   *
   * @param \Xylemical\Container\Definition\SourceInterface|null $original
   *   The original source.
   */
  public function __construct(?SourceInterface $original = NULL) {
    if (!$original) {
      return;
    }

    $this->class = $original->getClass();
    $this->serviceBuilders = $original->getServiceBuilders();
    $this->propertyBuilders = $original->getPropertyBuilders();
    $this->argumentBuilders = $original->getArgumentBuilders();
    $this->modifiers = $original->getModifiers();
    $this->timestamp = $original->getTimestamp();
    foreach ($original->getServices() as $service) {
      $service = clone($service);
      $this->services[$service->getName()] = $service;
    }
  }

  /**
   * Set the definition class.
   *
   * @param string $class
   *   The definition class.
   *
   * @return $this
   */
  public function setClass(string $class): static {
    $this->class = $class;
    return $this;
  }

  /**
   * Get a service.
   *
   * @param string $name
   *   The service name.
   *
   * @return \Xylemical\Container\Definition\ServiceDefinition|null
   *   The service or NULL.
   */
  public function getService(string $name): ?ServiceDefinition {
    return $this->services[$name] ?? NULL;
  }

  /**
   * Check for service.
   *
   * @param string $name
   *   The service name.
   *
   * @return bool
   *   The result.
   */
  public function hasService(string $name): bool {
    return isset($this->services[$name]);
  }

  /**
   * Set a service definition.
   *
   * @param \Xylemical\Container\Definition\ServiceDefinition $definition
   *   The definition.
   *
   * @return $this
   */
  public function setService(ServiceDefinition $definition): static {
    $this->services[$definition->getName()] = $definition;
    return $this;
  }

  /**
   * Find tagged services.
   *
   * @param string $tag
   *   The tag.
   *
   * @return \Xylemical\Container\Definition\ServiceDefinition[]
   *   The tagged services.
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
   * Set the service builders.
   *
   * @param array $builders
   *   The service builders.
   *
   * @return $this
   */
  public function setServiceBuilders(array $builders): static {
    $this->serviceBuilders = [];
    foreach ($builders as $builder) {
      $this->addServiceBuilder($builder);
    }
    return $this;
  }

  /**
   * Check for service builder.
   *
   * @param string $class
   *   The service builder.
   *
   * @return bool
   *   The result.
   */
  public function hasServiceBuilder(string $class): bool {
    return in_array($class, $this->serviceBuilders);
  }

  /**
   * Add a service builder.
   *
   * @param string $class
   *   The service builder.
   *
   * @return $this
   */
  public function addServiceBuilder(string $class): static {
    if (!in_array($class, $this->serviceBuilders)) {
      $this->serviceBuilders[] = $class;
    }
    return $this;
  }

  /**
   * Remove a service builder.
   *
   * @param string $class
   *   The service builder.
   *
   * @return $this
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
   * Set all the argument builders.
   *
   * @param array $builders
   *   The argument builders.
   *
   * @return $this
   */
  public function setArgumentBuilders(array $builders): static {
    $this->argumentBuilders = [];
    foreach ($builders as $builder) {
      $this->addArgumentBuilder($builder);
    }
    return $this;
  }

  /**
   * Check for argument builder.
   *
   * @param string $class
   *   The argument builder.
   *
   * @return bool
   *   The result.
   */
  public function hasArgumentBuilder(string $class): bool {
    return in_array($class, $this->argumentBuilders);
  }

  /**
   * Add an argument builder.
   *
   * @param string $class
   *   The argument builder.
   *
   * @return $this
   */
  public function addArgumentBuilder(string $class): static {
    if (!in_array($class, $this->argumentBuilders)) {
      $this->argumentBuilders[] = $class;
    }
    return $this;
  }

  /**
   * Remove an argument builder.
   *
   * @param string $class
   *   The argument builder.
   *
   * @return $this
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
   * Set all the property builders.
   *
   * @param array $builders
   *   The property builders.
   *
   * @return $this
   */
  public function setPropertyBuilders(array $builders): static {
    $this->propertyBuilders = [];
    foreach ($builders as $builder) {
      $this->addPropertyBuilder($builder);
    }
    return $this;
  }

  /**
   * Check for property builder.
   *
   * @param string $class
   *   The property builder.
   *
   * @return bool
   *   The result.
   */
  public function hasPropertyBuilder(string $class): bool {
    return in_array($class, $this->propertyBuilders);
  }

  /**
   * Add a property builder.
   *
   * @param string $class
   *   The property builder.
   *
   * @return $this
   */
  public function addPropertyBuilder(string $class): static {
    if (!in_array($class, $this->propertyBuilders)) {
      $this->propertyBuilders[] = $class;
    }
    return $this;
  }

  /**
   * Remove a property builder.
   *
   * @param string $class
   *   The property builder.
   *
   * @return $this
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
   * Sets the timestamp.
   *
   * @param int|null $timestamp
   *   The timestamp.
   *
   * @return $this
   */
  public function setTimestamp(?int $timestamp): static {
    if (is_null($timestamp)) {
      $this->timestamp = time();
    }
    else {
      $this->timestamp = $timestamp;
    }
    return $this;
  }

}
