<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder;

use Xylemical\Container\Definition\ArgumentInterface;
use Xylemical\Container\Definition\ServiceInterface;

/**
 * Provides a base argument builder.
 */
abstract class ArgumentBuilderBase implements ArgumentBuilderInterface {

  /**
   * {@inheritdoc}
   */
  public function __construct() {
  }

  /**
   * {@inheritdoc}
   */
  abstract public function applies(mixed $argument, ServiceInterface $service): bool;

  /**
   * {@inheritdoc}
   */
  abstract public function build(mixed $argument, ServiceInterface $service, BuilderInterface $builder): ArgumentInterface;

}
