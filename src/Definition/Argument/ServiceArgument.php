<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition\Argument;

use Xylemical\Container\Definition\ArgumentInterface;

/**
 * Provides a service argument.
 */
class ServiceArgument implements ArgumentInterface {

  /**
   * The service name.
   *
   * @var string
   */
  protected string $service;

  /**
   * ServiceArgument constructor.
   *
   * @param string $service
   *   The service.
   */
  public function __construct(string $service) {
    $this->service = $service;
  }

  /**
   * {@inheritdoc}
   */
  public function compile(): string {
    return "\$this->get(" . var_export($this->service, TRUE) . ")";
  }

  /**
   * {@inheritdoc}
   */
  public function getDependencies(): array {
    return [$this->service];
  }

}
