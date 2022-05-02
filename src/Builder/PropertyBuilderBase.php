<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder;

use Xylemical\Container\Definition\PropertyInterface;
use Xylemical\Container\Definition\ServiceInterface;

/**
 * Provides a base property builder.
 */
abstract class PropertyBuilderBase implements PropertyBuilderInterface {

  /**
   * {@inheritdoc}
   */
  public function __construct() {
  }

  /**
   * {@inheritdoc}
   */
  abstract public function applies(string $name, mixed $property, ServiceInterface $service): bool;

  /**
   * {@inheritdoc}
   */
  abstract public function build(string $name, mixed $property, ServiceInterface $service, BuilderInterface $builder): PropertyInterface;

}
