<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder;

use Xylemical\Container\Definition\ArgumentInterface;
use Xylemical\Container\Definition\DefinitionInterface;
use Xylemical\Container\Definition\PropertyInterface;
use Xylemical\Container\Definition\ServiceDefinition;
use Xylemical\Container\Definition\ServiceInterface;

/**
 * Provides the definition builder.
 */
interface BuilderInterface {

  /**
   * Get the definition.
   *
   * @return \Xylemical\Container\Definition\DefinitionInterface
   *   The definition.
   *
   * @throws \Xylemical\Container\Exception\InvalidDefinitionException
   * @throws \Xylemical\Container\Exception\CyclicDefinitionException
   */
  public function getDefinition(): DefinitionInterface;

  /**
   * Get the service definition.
   *
   * @param \Xylemical\Container\Definition\ServiceDefinition $service
   *   The service source.
   *
   * @return \Xylemical\Container\Definition\ServiceInterface|null
   *   The definition or NULL.
   *
   * @throws \Xylemical\Container\Exception\InvalidDefinitionException
   */
  public function getService(ServiceDefinition $service): ?ServiceInterface;

  /**
   * Get the argument definition.
   *
   * @param \Xylemical\Container\Definition\ServiceInterface $service
   *   The service.
   * @param mixed $argument
   *   The argument source.
   *
   * @return \Xylemical\Container\Definition\ArgumentInterface|null
   *   The definition.
   *
   * @throws \Xylemical\Container\Exception\InvalidDefinitionException
   */
  public function getArgument(ServiceInterface $service, mixed $argument): ?ArgumentInterface;

  /**
   * Get the property definition.
   *
   * @param string $name
   *   The property name.
   * @param \Xylemical\Container\Definition\ServiceInterface $service
   *   The service.
   * @param mixed $property
   *   The property source.
   *
   * @return \Xylemical\Container\Definition\PropertyInterface|null
   *   The definition.
   *
   * @throws \Xylemical\Container\Exception\InvalidDefinitionException
   */
  public function getProperty(string $name, ServiceInterface $service, mixed $property): ?PropertyInterface;

}
