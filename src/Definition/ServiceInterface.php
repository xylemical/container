<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition;

/**
 * Provides a service definition.
 */
interface ServiceInterface {

  /**
   * Get the name of the service.
   *
   * @return string
   *   The name.
   */
  public function getName(): string;

  /**
   * Get the class used for the service.
   *
   * @return string
   *   The class.
   */
  public function getClass(): string;

  /**
   * Get the service arguments.
   *
   * @return \Xylemical\Container\Definition\ArgumentInterface[]
   *   The arguments.
   */
  public function getArguments(): array;

  /**
   * Get an argument.
   *
   * @param int $argument
   *   The argument.
   *
   * @return \Xylemical\Container\Definition\ArgumentInterface|null
   *   The argument.
   */
  public function getArgument(int $argument): ?ArgumentInterface;

  /**
   * Set the service arguments.
   *
   * @param \Xylemical\Container\Definition\ArgumentInterface[] $arguments
   *   The arguments.
   *
   * @return $this
   */
  public function setArguments(array $arguments): static;

  /**
   * Add to the service arguments.
   *
   * @param \Xylemical\Container\Definition\ArgumentInterface[] $arguments
   *   The arguments.
   *
   * @return $this
   */
  public function addArguments(array $arguments): static;

  /**
   * Add an argument to the service arguments.
   *
   * @param \Xylemical\Container\Definition\ArgumentInterface $argument
   *   The argument.
   *
   * @return $this
   */
  public function addArgument(ArgumentInterface $argument): static;

  /**
   * Remove an argument.
   *
   * @param int $argument
   *   The argument.
   *
   * @return $this
   */
  public function removeArgument(int $argument): static;

  /**
   * Get the service properties.
   *
   * @return \Xylemical\Container\Definition\PropertyInterface[]
   *   The properties.
   */
  public function getProperties(): array;

  /**
   * Get the service property.
   *
   * @param string $property
   *   The property name.
   *
   * @return \Xylemical\Container\Definition\PropertyInterface|null
   *   The property or NULL.
   */
  public function getProperty(string $property): ?PropertyInterface;

  /**
   * Check the service has a property.
   *
   * @param string $property
   *   The property.
   *
   * @return bool
   *   The result.
   */
  public function hasProperty(string $property): bool;

  /**
   * Set the service properties.
   *
   * @param \Xylemical\Container\Definition\PropertyInterface[] $properties
   *   The properties.
   *
   * @return $this
   */
  public function setProperties(array $properties): static;

  /**
   * Add to the service properties.
   *
   * @param \Xylemical\Container\Definition\PropertyInterface[] $properties
   *   The properties.
   *
   * @return $this
   */
  public function addProperties(array $properties): static;

  /**
   * Add a property to the service properties.
   *
   * @param \Xylemical\Container\Definition\PropertyInterface $property
   *   The property.
   *
   * @return $this
   */
  public function addProperty(PropertyInterface $property): static;

  /**
   * Get the service dependencies.
   *
   * @return string[]
   *   The service's service dependencies.
   */
  public function getDependencies(): array;

  /**
   * Compile the instantiation of the service.
   *
   * @return string
   *   The compiled code.
   */
  public function compile(): string;

}
