<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder;

use Xylemical\Container\Definition\Argument\ServiceArgument;
use Xylemical\Container\Definition\ArgumentInterface;
use Xylemical\Container\Definition\ServiceInterface;

/**
 * A test argument builder.
 */
class TestArgumentBuilder implements ArgumentBuilderInterface {

  /**
   * {@inheritdoc}
   */
  public function __construct() {
  }

  /**
   * {@inheritdoc}
   */
  public function build(mixed $argument, ServiceInterface $service, BuilderInterface $builder): ?ArgumentInterface {
    if ($argument === 10) {
      return new ServiceArgument('dummy');
    }
    return NULL;
  }

}
