<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder\Argument;

use Xylemical\Container\Builder\ArgumentBuilderBase;
use Xylemical\Container\Builder\BuilderInterface;
use Xylemical\Container\Definition\Argument\ServiceArgument;
use Xylemical\Container\Definition\ArgumentInterface;
use Xylemical\Container\Definition\ServiceInterface;

/**
 * Provides a service argument builder.
 */
class ServiceArgumentBuilder extends ArgumentBuilderBase {

  /**
   * {@inheritdoc}
   */
  public function applies(mixed $argument, ServiceInterface $service): bool {
    return is_string($argument) && str_starts_with($argument, '@');
  }

  /**
   * {@inheritdoc}
   */
  public function build(mixed $argument, ServiceInterface $service, BuilderInterface $builder): ArgumentInterface {
    return new ServiceArgument(substr($argument, 1));
  }

}
