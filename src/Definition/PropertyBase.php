<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition;

/**
 * Provides a base for property definitions.
 */
abstract class PropertyBase implements PropertyInterface {

  /**
   * The name of the property.
   *
   * @var string
   */
  protected string $name;

  /**
   * PropertyBase constructor.
   *
   * @param string $name
   *   The name.
   */
  public function __construct(string $name) {
    $this->name = $name;
  }

  /**
   * {@inheritdoc}
   */
  public function getName(): string {
    return $this->name;
  }

  /**
   * {@inheritdoc}
   */
  abstract public function compile(string $service): string;

  /**
   * {@inheritdoc}
   */
  public function getDependencies(): array {
    return [];
  }

}
