<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder;

use Xylemical\Container\Definition\Service;
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
  public function applies(mixed $service): bool {
    return is_array($service) && isset($service['class']);
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Xylemical\Container\Exception\InvalidDefinitionException
   */
  public function build(mixed $service, BuilderInterface $builder): ServiceInterface {
    $class = (string) ($service['class'] ?? NULL);
    $name = (string) ($service['name'] ?? $class);
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
   * @param array $definition
   *   The definition.
   * @param \Xylemical\Container\Definition\ServiceInterface $service
   *   The service.
   * @param \Xylemical\Container\Builder\BuilderInterface $builder
   *   The builder.
   *
   * @throws \Xylemical\Container\Exception\InvalidDefinitionException
   */
  protected function doArguments(array $definition, ServiceInterface $service, BuilderInterface $builder): void {
    if (isset($definition['arguments']) && is_array($definition['arguments'])) {
      foreach ($definition['arguments'] as $argument) {
        if ($argument = $builder->getArgument($service, $argument)) {
          $service->addArgument($argument);
        }
      }
    }
  }

  /**
   * Build the properties for the service.
   *
   * @param array $definition
   *   The definition.
   * @param \Xylemical\Container\Definition\ServiceInterface $service
   *   The service.
   * @param \Xylemical\Container\Builder\BuilderInterface $builder
   *   The builder.
   *
   * @throws \Xylemical\Container\Exception\InvalidDefinitionException
   */
  protected function doProperties(array $definition, ServiceInterface $service, BuilderInterface $builder): void {
    unset($definition['class'], $definition['name'], $definition['arguments']);

    foreach ($definition as $key => $property) {
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
