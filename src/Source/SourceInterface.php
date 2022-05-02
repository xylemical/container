<?php

declare(strict_types=1);

namespace Xylemical\Container\Source;

/**
 * Provides the source for a definition.
 */
interface SourceInterface {

  /**
   * The class used for the definition.
   *
   * @return string
   *   The class.
   */
  public function getClass(): string;

  /**
   * Get the source definition.
   *
   * @return array
   *   The source definition.
   */
  public function getDefinition(): array;

  /**
   * Get the service builders.
   *
   * @return \Xylemical\Container\Builder\ServiceBuilderInterface[]
   *   The builders.
   */
  public function getServiceBuilders(): array;

  /**
   * Get the argument builders.
   *
   * @return \Xylemical\Container\Builder\ArgumentBuilderInterface[]
   *   The builders.
   */
  public function getArgumentBuilders(): array;

  /**
   * Get the property builders.
   *
   * @return \Xylemical\Container\Builder\PropertyBuilderInterface[]
   *   The builders.
   */
  public function getPropertyBuilders(): array;

  /**
   * The timestamp for when the sources were last updated.
   *
   * @return int
   *   The timestamp.
   */
  public function getTimestamp(): int;

}
