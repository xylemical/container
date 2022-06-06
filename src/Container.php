<?php

declare(strict_types=1);

namespace Xylemical\Container;

use Psr\Container\ContainerInterface;
use Xylemical\Container\Exception\NotFoundException;
use function call_user_func;
use function is_callable;

/**
 * Provides the base container for execution.
 */
class Container implements ContainerInterface {

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
   * The dynamic services.
   *
   * @var array
   */
  protected array $dynamic = [];

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
    if (isset($this->services[$id])) {
      return $this->services[$id];
    }

    if (isset(static::SERVICES[$id])) {
      $this->services[$id] = call_user_func([$this, static::SERVICES[$id]]);
    }
    elseif (isset($this->dynamic[$id]) && is_callable($this->dynamic[$id])) {
      $this->services[$id] = ($this->dynamic[$id])();
    }
    else {
      throw new NotFoundException("Unable to find service definition {$id}.");
    }

    return $this->services[$id];
  }

  /**
   * {@inheritdoc}
   */
  public function has(string $id): bool {
    return isset(static::SERVICES[$id]) ||
      isset($this->dynamic[$id]) ||
      isset($this->services[$id]);
  }

  /**
   * Add additional dynamic services.
   *
   * @param array $services
   *   The services.
   *
   * @return $this
   */
  public function add(array $services): static {
    $this->dynamic += $services;
    return $this;
  }

}
