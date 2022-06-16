<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder\Property;

use Xylemical\Container\Builder\BuilderInterface;
use Xylemical\Container\Builder\PropertyBuilderBase;
use Xylemical\Container\Definition\Property\ServiceCollectorProperty;
use Xylemical\Container\Definition\PropertyInterface;
use Xylemical\Container\Definition\ServiceInterface;

/**
 * Provides a service collection property.
 */
class ServiceCollectorPropertyBuilder extends PropertyBuilderBase {

  /**
   * {@inheritdoc}
   */
  public function build(string $name, mixed $property, ServiceInterface $service, BuilderInterface $builder): ?PropertyInterface {
    if ($name === 'service.collector') {
      return new ServiceCollectorProperty($property['method'], $property['services']);
    }
    return NULL;
  }

}
