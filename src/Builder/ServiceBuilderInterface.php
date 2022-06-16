<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder;

use Xylemical\Container\Definition\ServiceDefinition;
use Xylemical\Container\Definition\ServiceInterface;

/**
 * Provides for building services.
 */
interface ServiceBuilderInterface {

  /**
   * ServiceBuilderInterface constructor.
   */
  public function __construct();

  /**
   * Get the service definition.
   *
   * @param \Xylemical\Container\Definition\ServiceDefinition $service
   *   The service source.
   * @param \Xylemical\Container\Builder\BuilderInterface $builder
   *   The builder.
   *
   * @return \Xylemical\Container\Definition\ServiceInterface|null
   *   The service.
   */
  public function build(ServiceDefinition $service, BuilderInterface $builder): ?ServiceInterface;

}
