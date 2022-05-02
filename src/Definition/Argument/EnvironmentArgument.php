<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition\Argument;

use Xylemical\Container\Definition\ArgumentInterface;

/**
 * Provides an environment variable argument.
 */
class EnvironmentArgument implements ArgumentInterface {

  /**
   * The service name.
   *
   * @var string
   */
  protected string $variable;

  /**
   * The default value.
   *
   * @var mixed
   */
  protected mixed $default;

  /**
   * EnvironmentArgument constructor.
   *
   * @param string $variable
   *   The variable.
   * @param mixed|null $default
   *   The default value for the argument.
   */
  public function __construct(string $variable, mixed $default = NULL) {
    $this->variable = $variable;
    $this->default = $default;
  }

  /**
   * Get the environment variable.
   *
   * @return string
   *   The code.
   */
  protected function getEnv(): string {
    return "getenv(" . var_export($this->variable, TRUE) . ")";
  }

  /**
   * Get the default value.
   *
   * @return string
   *   The code.
   */
  protected function getDefault(): string {
    return var_export($this->default, TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function compile(): string {
    $code = $this->getEnv();
    if (!is_null($this->default)) {
      return "{$code} !== FALSE ? {$this->getEnv()} : {$this->getDefault()}";
    }
    return $code;
  }

  /**
   * {@inheritdoc}
   */
  public function getDependencies(): array {
    return [];
  }

}
