<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition\Argument;

use Xylemical\Container\Definition\ArgumentInterface;

/**
 * Provides a value service argument.
 */
class ValueArgument implements ArgumentInterface {

  /**
   * The value for the argument.
   *
   * @var mixed
   */
  protected mixed $value;

  /**
   * ValueArgument constructor.
   *
   * @param mixed $value
   *   The argument.
   */
  public function __construct(mixed $value) {
    $this->value = $value;
  }

  /**
   * {@inheritdoc}
   */
  public function compile(): string {
    return var_export($this->value, TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function getDependencies(): array {
    return [];
  }

}
