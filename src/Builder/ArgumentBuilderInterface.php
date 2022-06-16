<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder;

use Xylemical\Container\Definition\ArgumentInterface;
use Xylemical\Container\Definition\ServiceInterface;

/**
 * Provides for building arguments.
 */
interface ArgumentBuilderInterface {

  /**
   * ArgumentBuilderInterface constructor.
   */
  public function __construct();

  /**
   * Get the argument definition.
   *
   * @param mixed $argument
   *   The argument source definition.
   * @param \Xylemical\Container\Definition\ServiceInterface $service
   *   The service.
   * @param \Xylemical\Container\Builder\BuilderInterface $builder
   *   The builder.
   *
   * @return \Xylemical\Container\Definition\ArgumentInterface|null
   *   The argument.
   */
  public function build(mixed $argument, ServiceInterface $service, BuilderInterface $builder): ?ArgumentInterface;

}
