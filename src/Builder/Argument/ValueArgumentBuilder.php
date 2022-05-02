<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder\Argument;

use Xylemical\Container\Builder\ArgumentBuilderBase;
use Xylemical\Container\Builder\BuilderInterface;
use Xylemical\Container\Definition\Argument\ValueArgument;
use Xylemical\Container\Definition\ArgumentInterface;
use Xylemical\Container\Definition\ServiceInterface;

/**
 * Provides a value argument builder.
 */
class ValueArgumentBuilder extends ArgumentBuilderBase {

  /**
   * {@inheritdoc}
   */
  public function applies(mixed $argument, ServiceInterface $service): bool {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function build(mixed $argument, ServiceInterface $service, BuilderInterface $builder): ArgumentInterface {
    return new ValueArgument($argument);
  }

}
