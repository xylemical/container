<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder;

use Xylemical\Container\Definition\Service;
use Xylemical\Container\Definition\ServiceDefinition;
use Xylemical\Container\Definition\ServiceInterface;
use Xylemical\Container\Exception\InvalidDefinitionException;

/**
 * Provides a base service builder.
 */
abstract class ServiceBuilderBase implements ServiceBuilderInterface {

  /**
   * {@inheritdoc}
   */
  public function __construct() {
  }

  /**
   * {@inheritdoc}
   */
  public function applies(ServiceDefinition $service): bool {
    return class_exists($service->getClass());
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Xylemical\Container\Exception\InvalidDefinitionException
   */
  public function build(ServiceDefinition $service, BuilderInterface $builder): ServiceInterface {
    $class = $service->getClass();
    $name = $service->getName();
    if (!class_exists($class) || !(class_exists($name) || interface_exists($name))) {
      throw new InvalidDefinitionException();
    }

    $object = $this->doService($name, $class);
    $this->doArguments($service, $object, $builder);
    $this->doProperties($service, $object, $builder);

    return $object;
  }

  /**
   * Build the arguments for the service.
   *
   * @param \Xylemical\Container\Definition\ServiceDefinition $definition
   *   The definition.
   * @param \Xylemical\Container\Definition\ServiceInterface $service
   *   The service.
   * @param \Xylemical\Container\Builder\BuilderInterface $builder
   *   The builder.
   *
   * @throws \Xylemical\Container\Exception\InvalidDefinitionException
   */
  protected function doArguments(ServiceDefinition $definition, ServiceInterface $service, BuilderInterface $builder): void {
    foreach ($definition->getArguments() as $argument) {
      if ($argument = $builder->getArgument($service, $argument)) {
        $service->addArgument($argument);
      }
    }
  }

  /**
   * Build the properties for the service.
   *
   * @param \Xylemical\Container\Definition\ServiceDefinition $definition
   *   The definition.
   * @param \Xylemical\Container\Definition\ServiceInterface $service
   *   The service.
   * @param \Xylemical\Container\Builder\BuilderInterface $builder
   *   The builder.
   *
   * @throws \Xylemical\Container\Exception\InvalidDefinitionException
   */
  protected function doProperties(ServiceDefinition $definition, ServiceInterface $service, BuilderInterface $builder): void {
    foreach ($definition->getProperties() as $key => $property) {
      if ($property = $builder->getProperty($key, $service, $property)) {
        $service->addProperty($property);
      }
    }
  }

  /**
   * Create the service.
   *
   * @param string $name
   *   The name.
   * @param string $class
   *   The class.
   *
   * @return \Xylemical\Container\Definition\ServiceInterface
   *   The service.
   */
  protected function doService(string $name, string $class): ServiceInterface {
    return new Service($name, $class);
  }

}
