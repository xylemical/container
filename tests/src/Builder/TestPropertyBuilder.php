<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder;

use Xylemical\Container\Definition\PropertyInterface;
use Xylemical\Container\Definition\ServiceInterface;
use Xylemical\Container\Definition\TestProperty;

/**
 * A test property builder.
 */
class TestPropertyBuilder implements PropertyBuilderInterface {

  /**
   * {@inheritdoc}
   */
  public function __construct() {
  }

  /**
   * {@inheritdoc}
   */
  public function build(string $name, mixed $property, ServiceInterface $service, BuilderInterface $builder): ?PropertyInterface {
    if ($name === 'test') {
      return new TestProperty();
    }
    return NULL;
  }

}
