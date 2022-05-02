<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition;

/**
 * The argument for the service.
 */
interface ArgumentInterface {

  /**
   * Compile the argument to be provided to the service instantiation.
   *
   * @return string
   *   The compiled argument.
   */
  public function compile(): string;

  /**
   * Get the service dependencies.
   *
   * @return string[]
   *   The dependencies.
   */
  public function getDependencies(): array;

}
