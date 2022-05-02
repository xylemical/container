<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition;

/**
 * The source for the container.
 */
interface DefinitionInterface {

  /**
   * DefinitionInterface constructor.
   *
   * @param string $class
   *   The class.
   * @param \Xylemical\Container\Definition\ServiceInterface[] $services
   *   The services.
   */
  public function __construct(string $class, array $services);

  /**
   * Get the class for the definition.
   *
   * @return string
   *   The class.
   */
  public function getClass(): string;

  /**
   * The service definitions.
   *
   * @return \Xylemical\Container\Definition\ServiceInterface[]
   *   The services.
   */
  public function getServices(): array;

  /**
   * Get service.
   *
   * @param string $service
   *   The service.
   *
   * @return \Xylemical\Container\Definition\ServiceInterface|null
   *   The service.
   */
  public function getService(string $service): ?ServiceInterface;

  /**
   * Set the services of the definition.
   *
   * @param \Xylemical\Container\Definition\ServiceInterface[] $services
   *   The services.
   *
   * @return $this
   */
  public function setServices(array $services): static;

  /**
   * Add services to the definition.
   *
   * @param \Xylemical\Container\Definition\ServiceInterface[] $services
   *   The services.
   *
   * @return $this
   */
  public function addServices(array $services): static;

  /**
   * Add a service.
   *
   * @param \Xylemical\Container\Definition\ServiceInterface $service
   *   The service.
   *
   * @return $this
   */
  public function addService(ServiceInterface $service): static;

  /**
   * Remove a service from the definition.
   *
   * @param string $name
   *   The service name.
   *
   * @return $this
   */
  public function removeService(string $name): static;

}
