<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder\Argument;

use Xylemical\Container\Builder\ArgumentBuilderBase;
use Xylemical\Container\Builder\BuilderInterface;
use Xylemical\Container\Definition\Argument\EnvironmentArgument;
use Xylemical\Container\Definition\ArgumentInterface;
use Xylemical\Container\Definition\ServiceInterface;

/**
 * Provides an environment argument builder.
 */
class EnvironmentArgumentBuilder extends ArgumentBuilderBase {

  /**
   * {@inheritdoc}
   */
  public function build(mixed $argument, ServiceInterface $service, BuilderInterface $builder): ?ArgumentInterface {
    if (is_string($argument) && preg_match('/^%(.*?)(?::(.*?))?%$/', $argument, $match)) {
      return new EnvironmentArgument($match[1], $match[2] ?? NULL);
    }
    return NULL;
  }

}
