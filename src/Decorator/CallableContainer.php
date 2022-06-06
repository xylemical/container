<?php

declare(strict_types=1);

namespace Xylemical\Container\Decorator;

use Psr\Container\ContainerInterface;
use Xylemical\Container\ContainerDecoratedInterface;

/**
 * Provides a callable decorator container.
 */
class CallableContainer implements ContainerDecoratedInterface {

  /**
   * The decorated container.
   *
   * @var \Psr\Container\ContainerInterface
   */
  protected ContainerInterface $container;

  /**
   * The callables.
   *
   * @var array
   */
  protected array $callables = [];

  /**
   * CallableContainer constructor.
   *
   * @param \Psr\Container\ContainerInterface $container
   *   The container.
   * @param array $callables
   *   The callables.
   */
  public function __construct(ContainerInterface $container, array $callables = []) {
    if ($container instanceof ContainerDecoratedInterface) {
      $container->setRoot($this);
    }

    $this->container = $container;
    $this->callables = $callables;
  }

  /**
   * {@inheritdoc}
   */
  public function get(string $id) {
    if (isset($this->callables[$id]) && is_callable($this->callables[$id])) {
      return ($this->callables[$id])();
    }
    return $this->container->get($id);
  }

  /**
   * {@inheritdoc}
   */
  public function has(string $id): bool {
    return isset($this->callables[$id]) || $this->container->has($id);
  }

  /**
   * {@inheritdoc}
   */
  public function setRoot(?ContainerInterface $container): static {
    if ($this->container instanceof ContainerDecoratedInterface) {
      $this->container->setRoot($container ?: $this);
    }
    return $this;
  }

}
