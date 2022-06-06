<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition;

/**
 * Provide a base modifier.
 */
abstract class ModifierBase implements ModifierInterface {

  /**
   * The priority.
   *
   * @var int
   */
  protected int $priority = 0;

  /**
   * {@inheritdoc}
   */
  public function __construct() {
  }

  /**
   * {@inheritdoc}
   */
  public function getPriority(): int {
    return $this->priority;
  }

  /**
   * {@inheritdoc}
   */
  abstract public function apply(Source $source): void;

}
