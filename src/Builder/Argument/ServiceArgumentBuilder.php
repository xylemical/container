<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder\Argument;

use Xylemical\Container\Builder\ArgumentBuilderBase;
use Xylemical\Container\Builder\BuilderInterface;
use Xylemical\Container\Definition\Argument\ServiceArgument;
use Xylemical\Container\Definition\ArgumentInterface;
use Xylemical\Container\Definition\ServiceInterface;
use function is_string;

/**
 * Provides a service argument builder.
 */
class ServiceArgumentBuilder extends ArgumentBuilderBase {

  /**
   * {@inheritdoc}
   */
  public function build(mixed $argument, ServiceInterface $service, BuilderInterface $builder): ?ArgumentInterface {
    if (is_string($argument) && str_starts_with($argument, '@')) {
      return new ServiceArgument(substr($argument, 1));
    }
    return NULL;
  }

}
