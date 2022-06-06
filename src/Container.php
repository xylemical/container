<?php

declare(strict_types=1);

namespace Xylemical\Container;

use Psr\Container\ContainerInterface;
use Xylemical\Container\Exception\NotFoundException;

/**
 * Provides the base container for execution.
 */
class Container implements ContainerDecoratedInterface {

  /**
   * The service callbacks used to create the services.
   */
  protected const SERVICES = [];

  /**
   * The services available.
   *
   * @var array
   */
  protected array $services = [];

  /**
   * The root container.
   *
   * @var \Psr\Container\ContainerInterface|null
   */
  protected ?ContainerInterface $root = NULL;

  /**
   * Container constructor.
   *
   * @param array $initial
   *   The initial services.
   */
  public function __construct(array $initial = []) {
    $this->services = $initial;
  }

  /**
   * Returns the service.
   *
   * @param class-string<SERVICE> $id
   *   The class string.
   *
   * @template SERVICE
   *
   * @return SERVICE
   *   The service
   *
   * @throws \Xylemical\Container\Exception\NotFoundException
   * @throws \Psr\Container\ContainerExceptionInterface
   */
  public function get(string $id) {
    if ($this->root?->has($id)) {
      return $this->root->get($id);
    }

    if (isset($this->services[$id])) {
      return $this->services[$id];
    }

    if (!isset(static::SERVICES[$id])) {
      throw new NotFoundException("Unable to find service definition {$id}.");
    }

    $this->services[$id] = call_user_func([$this, static::SERVICES[$id]]);
    return $this->services[$id];
  }

  /**
   * {@inheritdoc}
   */
  public function has(string $id): bool {
    return isset(static::SERVICES[$id]) || isset($this->services[$id]);
  }

  /**
   * {@inheritdoc}
   */
  public function setRoot(?ContainerInterface $container): static {
    $this->root = $container;
    return $this;
  }

}
