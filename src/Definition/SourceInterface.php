<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition;

/**
 * Provides the source for a definition.
 */
interface SourceInterface {

  /**
   * Load the source.
   *
   * This is required to be called before calling any of the other functions,
   * as the source is to be lazy-loaded.
   *
   * @return $this
   */
  public function load(): static;

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
   * @return \Xylemical\Container\Definition\ServiceDefinition[]
   *   The service definition.
   */
  public function getServices(): array;

  /**
   * Get the service builders.
   *
   * @return string[]
   *   The builders.
   */
  public function getServiceBuilders(): array;

  /**
   * Get the argument builders.
   *
   * @return string[]
   *   The builders.
   */
  public function getArgumentBuilders(): array;

  /**
   * Get the property builders.
   *
   * @return string[]
   *   The builders.
   */
  public function getPropertyBuilders(): array;

  /**
   * Get the source modifiers.
   *
   * @return string[]
   *   The modifiers.
   */
  public function getModifiers(): array;

  /**
   * The timestamp for when the sources were last updated.
   *
   * @return int
   *   The timestamp.
   */
  public function getTimestamp(): int;

}
