<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition\Property;

use Xylemical\Container\Definition\PropertyBase;

/**
 * Provides the service collection property.
 */
class ServiceCollectorProperty extends PropertyBase {

  /**
   * The method used to collect the services.
   *
   * @var string
   */
  protected string $method;

  /**
   * The collected services.
   *
   * @var string[]
   */
  protected array $services;

  /**
   * ServiceCollectorProperty constructor.
   *
   * @param string $method
   *   The collector method.
   * @param string[] $services
   *   The services.
   */
  public function __construct(string $method, array $services) {
    parent::__construct('service.collector');
    $this->method = $method;
    $this->services = $services;
  }

  /**
   * {@inheritdoc}
   */
  public function compile(string $service): string {
    $output = '';
    foreach ($this->services as $target) {
      $target = var_export($target, TRUE);
      $output .= "{$service}->{$this->method}(\$this->get({$target}));\n";
    }
    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function getDependencies(): array {
    return $this->services;
  }

}
