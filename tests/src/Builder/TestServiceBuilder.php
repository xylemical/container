<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder;

use Xylemical\Container\Definition\ServiceInterface;
use Xylemical\Container\Definition\TestService;

/**
 * Test service builder.
 */
class TestServiceBuilder extends ServiceBuilderBase {

  /**
   * {@inheritdoc}
   */
  protected function doService(string $name, string $class): ServiceInterface {
    return new TestService($name, $class);
  }

}
