<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition;

/**
 * A test property.
 */
class TestProperty implements PropertyInterface {

  /**
   * {@inheritdoc}
   */
  public function getName(): string {
    return 'property';
  }

  /**
   * {@inheritdoc}
   */
  public function compile(string $service): string {
    return "{$service}->addProperty('foo', 'bar');";
  }

  /**
   * {@inheritdoc}
   */
  public function getDependencies(): array {
    return [ServiceInterface::class];
  }

}
