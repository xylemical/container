<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition;

/**
 * Allows augmentation of the service container.
 */
interface PropertyInterface {

  /**
   * The name of the property.
   *
   * @return string
   *   The name.
   */
  public function getName(): string;

  /**
   * Compile the container property after the initialization of the service.
   *
   * @param string $service
   *   The service variable.
   *
   * @return string
   *   The compiled code.
   */
  public function compile(string $service): string;

  /**
   * Get the service dependencies.
   *
   * @return string[]
   *   The dependencies.
   */
  public function getDependencies(): array;

}
