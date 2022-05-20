<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition;

/**
 * Provides the source for a definition.
 */
interface SourceInterface {

  /**
   * Load the source.
   *
   * This is required to be called before calling any of the other functions,
   * as the source is to be lazy-loaded.
   *
   * @return $this
   */
  public function load(): static;

  /**
   * The class used for the definition.
   *
   * @return string
   *   The class.
   */
  public function getClass(): string;

  /**
   * Set the class of the definition.
   *
   * @param string $class
   *   The class.
   *
   * @return $this
   */
  public function setClass(string $class): static;

  /**
   * Get the source definition.
   *
   * @return \Xylemical\Container\Definition\ServiceDefinition[]
   *   The service definition.
   */
  public function getServices(): array;

  /**
   * Get a service.
   *
   * @param string $class
   *   The service class.
   *
   * @return \Xylemical\Container\Definition\ServiceDefinition|null
   *   The definition or NULL.
   */
  public function getService(string $class): ?ServiceDefinition;

  /**
   * Check the service definition exists.
   *
   * @param string $class
   *   The class.
   *
   * @return bool
   *   The result.
   */
  public function hasService(string $class): bool;

  /**
   * Set the definition.
   *
   * @param \Xylemical\Container\Definition\ServiceDefinition $definition
   *   The definition.
   *
   * @return $this
   */
  public function setService(ServiceDefinition $definition): static;

  /**
   * Find all services with a given tag.
   *
   * @param string $tag
   *   The tag.
   *
   * @return \Xylemical\Container\Definition\ServiceDefinition[]
   *   The services.
   */
  public function findTaggedServices(string $tag): array;

  /**
   * Get the service builders.
   *
   * @return string[]
   *   The builders.
   */
  public function getServiceBuilders(): array;

  /**
   * Set the service builders.
   *
   * @param string[] $builders
   *   The builders.
   *
   * @return $this
   */
  public function setServiceBuilders(array $builders): static;

  /**
   * Check there is a service builder.
   *
   * @param string $class
   *   The class.
   *
   * @return bool
   *   The result.
   */
  public function hasServiceBuilder(string $class): bool;

  /**
   * Add a service builder.
   *
   * @param string $class
   *   The class.
   *
   * @return $this
   */
  public function addServiceBuilder(string $class): static;

  /**
   * Remove a service builder.
   *
   * @param string $class
   *   The class.
   *
   * @return $this
   */
  public function removeServiceBuilder(string $class): static;

  /**
   * Get the argument builders.
   *
   * @return string[]
   *   The builders.
   */
  public function getArgumentBuilders(): array;

  /**
   * Set the argument builders.
   *
   * @param string[] $builders
   *   The builders.
   *
   * @return $this
   */
  public function setArgumentBuilders(array $builders): static;

  /**
   * Check there is a argument builder.
   *
   * @param string $class
   *   The class.
   *
   * @return bool
   *   The result.
   */
  public function hasArgumentBuilder(string $class): bool;

  /**
   * Add a argument builder.
   *
   * @param string $class
   *   The class.
   *
   * @return $this
   */
  public function addArgumentBuilder(string $class): static;

  /**
   * Remove a argument builder.
   *
   * @param string $class
   *   The class.
   *
   * @return $this
   */
  public function removeArgumentBuilder(string $class): static;

  /**
   * Get the property builders.
   *
   * @return string[]
   *   The builders.
   */
  public function getPropertyBuilders(): array;

  /**
   * Set the property builders.
   *
   * @param string[] $builders
   *   The builders.
   *
   * @return $this
   */
  public function setPropertyBuilders(array $builders): static;

  /**
   * Check there is a property builder.
   *
   * @param string $class
   *   The class.
   *
   * @return bool
   *   The result.
   */
  public function hasPropertyBuilder(string $class): bool;

  /**
   * Add a property builder.
   *
   * @param string $class
   *   The class.
   *
   * @return $this
   */
  public function addPropertyBuilder(string $class): static;

  /**
   * Remove a property builder.
   *
   * @param string $class
   *   The class.
   *
   * @return $this
   */
  public function removePropertyBuilder(string $class): static;

  /**
   * The timestamp for when the sources were last updated.
   *
   * @return int
   *   The timestamp.
   */
  public function getTimestamp(): int;

}
