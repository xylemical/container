<?php

declare(strict_types=1);

namespace Xylemical\Container\Source;

/**
 * A test modifier.
 */
class TestModifier implements ModifierInterface {

  /**
   * The priority.
   *
   * @var int
   */
  public int $priority = 0;

  /**
   * The modifier name.
   *
   * @var string
   */
  public string $name = 'test';

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
  public function apply(array &$definition): void {
    $definition['modifier'][] = $this->name;
  }

}
