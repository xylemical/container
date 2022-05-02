<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition;

/**
 * Provides a generic definition.
 */
class Definition implements DefinitionInterface {

  /**
   * The container class.
   *
   * @var string
   */
  protected string $class;

  /**
   * The services.
   *
   * @var \Xylemical\Container\Definition\ServiceInterface[]
   */
  protected array $definitions = [];

  /**
   * {@inheritdoc}
   */
  public function __construct(string $class, array $services = []) {
    $this->class = $class;
    $this->addServices($services);
  }

  /**
   * {@inheritdoc}
   */
  public function getClass(): string {
    return $this->class;
  }

  /**
   * {@inheritdoc}
   */
  public function getServices(): array {
    return $this->definitions;
  }

  /**
   * {@inheritdoc}
   */
  public function getService(string $service): ?ServiceInterface {
    return $this->definitions[$service] ?? NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function setServices(array $services): static {
    $this->definitions = [];
    $this->addServices($services);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function addServices(array $services): static {
    foreach ($services as $service) {
      $this->addService($service);
    }
    return $this;

  }

  /**
   * {@inheritdoc}
   */
  public function addService(ServiceInterface $service): static {
    $this->definitions[$service->getName()] = $service;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function removeService(string $name): static {
    unset($this->definitions[$name]);
    return $this;
  }

}
