<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder;

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
   * Check the builder applies to the service source definition.
   *
   * @param mixed $service
   *   The service source definition.
   *
   * @return bool
   *   The result.
   */
  public function applies(mixed $service): bool;

  /**
   * Get the service definition.
   *
   * @param mixed $service
   *   The service source.
   * @param \Xylemical\Container\Builder\BuilderInterface $builder
   *   The builder.
   *
   * @return \Xylemical\Container\Definition\ServiceInterface
   *   The service.
   */
  public function build(mixed $service, BuilderInterface $builder): ServiceInterface;

}
