<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition;

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
  public function apply(SourceInterface $source): void {
    // @phpstan-ignore-next-line
    $source->modifier[] = $this->name;
  }

}
