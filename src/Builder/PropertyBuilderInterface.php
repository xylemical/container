<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder;

use Xylemical\Container\Definition\PropertyInterface;
use Xylemical\Container\Definition\ServiceInterface;

/**
 * Provides for building a property definition.
 */
interface PropertyBuilderInterface {

  /**
   * PropertyBuilderInterface constructor.
   */
  public function __construct();

  /**
   * Check the builder applies to the property source definition.
   *
   * @param string $name
   *   The property name.
   * @param mixed $property
   *   The property definition.
   * @param \Xylemical\Container\Definition\ServiceInterface $service
   *   The service.
   *
   * @return bool
   *   The result.
   */
  public function applies(string $name, mixed $property, ServiceInterface $service): bool;

  /**
   * Get the property definition.
   *
   * @param string $name
   *   The property name.
   * @param mixed $property
   *   The property definition.
   * @param \Xylemical\Container\Definition\ServiceInterface $service
   *   The service.
   * @param \Xylemical\Container\Builder\BuilderInterface $builder
   *   The property builder.
   *
   * @return \Xylemical\Container\Definition\PropertyInterface
   *   The property.
   */
  public function build(string $name, mixed $property, ServiceInterface $service, BuilderInterface $builder): PropertyInterface;

}
