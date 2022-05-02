<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder;

use Xylemical\Container\Builder\Argument\EnvironmentArgumentBuilder;
use Xylemical\Container\Builder\Argument\ServiceArgumentBuilder;
use Xylemical\Container\Builder\Argument\ValueArgumentBuilder;
use Xylemical\Container\Builder\Service\GenericServiceBuilder;
use Xylemical\Container\Definition\ArgumentInterface;
use Xylemical\Container\Definition\DefinitionInterface;
use Xylemical\Container\Definition\PropertyInterface;
use Xylemical\Container\Definition\ServiceInterface;
use Xylemical\Container\Exception\InvalidDefinitionException;
use Xylemical\Container\Source\SourceInterface;

/**
 * Provides a builder for a definition.
 */
class Builder implements BuilderInterface {

  /**
   * The container class name.
   *
   * @var string
   */
  protected string $class;

  /**
   * Get the source.
   *
   * @var \Xylemical\Container\Source\SourceInterface
   */
  protected SourceInterface $source;

  /**
   * The service builder.
   *
   * @var \Xylemical\Container\Builder\ServiceBuilder
   */
  protected ServiceBuilder $serviceBuilder;

  /**
   * The argument builder.
   *
   * @var \Xylemical\Container\Builder\ArgumentBuilder
   */
  protected ArgumentBuilder $argumentBuilder;

  /**
   * The property builder.
   *
   * @var \Xylemical\Container\Builder\PropertyBuilder
   */
  protected PropertyBuilder $propertyBuilder;

  /**
   * The built definition.
   *
   * @var \Xylemical\Container\Definition\DefinitionInterface|null
   */
  protected ?DefinitionInterface $definition = NULL;

  /**
   * Builder constructor.
   *
   * @param string $class
   *   The container class name.
   * @param \Xylemical\Container\Source\SourceInterface $source
   *   The source.
   */
  public function __construct(string $class, SourceInterface $source) {
    $this->class = $class;
    $this->source = $source;
    $this->serviceBuilder = (new ServiceBuilder())
      ->setBuilders($source->getServiceBuilders())
      ->addBuilders([
        new GenericServiceBuilder(),
      ]);
    $this->argumentBuilder = (new ArgumentBuilder())
      ->setBuilders($source->getArgumentBuilders())
      ->addBuilders([
        new EnvironmentArgumentBuilder(),
        new ServiceArgumentBuilder(),
        new ValueArgumentBuilder(),
      ]);
    $this->propertyBuilder = (new PropertyBuilder())
      ->setBuilders($source->getPropertyBuilders());
  }

  /**
   * {@inheritdoc}
   */
  public function getService(mixed $service): ?ServiceInterface {
    if ($this->serviceBuilder->applies($service)) {
      return $this->serviceBuilder->build($service, $this);
    }
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getArgument(ServiceInterface $service, mixed $argument): ?ArgumentInterface {
    // ValueArgument will always apply, so allow applies() checks to take place,
    // but it will always return TRUE.
    $this->argumentBuilder->applies($argument, $service);
    return $this->argumentBuilder->build($argument, $service, $this);
  }

  /**
   * {@inheritdoc}
   */
  public function getProperty(string $name, ServiceInterface $service, mixed $property): ?PropertyInterface {
    if ($this->propertyBuilder->applies($name, $property, $service)) {
      return $this->propertyBuilder->build($name, $property, $service, $this);
    }
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinition(): DefinitionInterface {
    if (!$this->definition) {
      $this->definition = $this->build();
    }
    return $this->definition;
  }

  /**
   * Builds the definition.
   *
   * @return \Xylemical\Container\Definition\DefinitionInterface
   *   The definition.
   *
   * @throws \Xylemical\Container\Exception\InvalidDefinitionException
   */
  protected function build(): DefinitionInterface {
    $definition = $this->source->getDefinition();
    $services = [];
    foreach ($definition as $service) {
      if ($service = $this->getService($service)) {
        $services[] = $service;
      }
    }
    return $this->buildDefinition($services);
  }

  /**
   * Build the definition.
   *
   * @param \Xylemical\Container\Definition\ServiceInterface[] $services
   *   The services.
   *
   * @return \Xylemical\Container\Definition\DefinitionInterface
   *   The definition.
   *
   * @throws \Xylemical\Container\Exception\InvalidDefinitionException
   */
  protected function buildDefinition(array $services): DefinitionInterface {
    $class = $this->source->getClass();
    if (class_exists($class) && is_subclass_of($class, DefinitionInterface::class)) {
      return new $class($this->class, $services);
    }
    throw new InvalidDefinitionException();
  }

}
