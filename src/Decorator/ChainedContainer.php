<?php

declare(strict_types=1);

namespace Xylemical\Container\Decorator;

use Psr\Container\ContainerInterface;

/**
 * Provides a chained decorator container.
 */
class ChainedContainer implements ContainerInterface {

  /**
   * The decorated container.
   *
   * @var \Psr\Container\ContainerInterface
   */
  protected ContainerInterface $container;

  /**
   * The primary container.
   *
   * @var \Psr\Container\ContainerInterface
   */
  protected ContainerInterface $primary;

  /**
   * ChainedContainer constructor.
   *
   * @param \Psr\Container\ContainerInterface $container
   *   The container.
   * @param \Psr\Container\ContainerInterface $primary
   *   The primary container.
   */
  public function __construct(ContainerInterface $container, ContainerInterface $primary) {
    $this->container = $container;
    $this->primary = $primary;
  }

  /**
   * {@inheritdoc}
   */
  public function get(string $id) {
    if ($this->primary->has($id)) {
      return $this->primary->get($id);
    }
    return $this->container->get($id);
  }

  /**
   * {@inheritdoc}
   */
  public function has(string $id): bool {
    return $this->primary->has($id) || $this->container->has($id);
  }

}
