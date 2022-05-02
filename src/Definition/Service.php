<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition;

use Xylemical\Code\Util\Indenter;

/**
 * Provides a container service.
 */
class Service implements ServiceInterface {

  /**
   * The service name.
   *
   * @var string
   */
  protected string $name;

  /**
   * The class used for the service.
   *
   * @var string
   */
  protected string $class;

  /**
   * The arguments.
   *
   * @var \Xylemical\Container\Definition\ArgumentInterface[]
   */
  protected array $arguments = [];

  /**
   * The properties.
   *
   * @var \Xylemical\Container\Definition\PropertyInterface[]
   */
  protected array $properties = [];

  /**
   * Service constructor.
   *
   * @param string $name
   *   The name.
   * @param string $class
   *   The class.
   */
  public function __construct(string $name, string $class) {
    $this->name = $name;
    $this->class = $class;
  }

  /**
   * {@inheritdoc}
   */
  public function getName(): string {
    return $this->name;
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
  public function getArguments(): array {
    return $this->arguments;
  }

  /**
   * {@inheritdoc}
   */
  public function getArgument(int $argument): ?ArgumentInterface {
    return $this->arguments[$argument] ?? NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function setArguments(array $arguments): static {
    $this->arguments = [];
    $this->addArguments($arguments);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function addArguments(array $arguments): static {
    foreach ($arguments as $argument) {
      $this->addArgument($argument);
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function addArgument(ArgumentInterface $argument): static {
    $this->arguments[] = $argument;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function removeArgument(int $argument): static {
    unset($this->arguments[$argument]);
    $this->arguments = array_values($this->arguments);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getProperties(): array {
    return $this->properties;
  }

  /**
   * {@inheritdoc}
   */
  public function getProperty(string $property): ?PropertyInterface {
    return $this->properties[$property] ?? NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function hasProperty(string $property): bool {
    return isset($this->properties[$property]);
  }

  /**
   * {@inheritdoc}
   */
  public function setProperties(array $properties): static {
    $this->properties = [];
    $this->addProperties($properties);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function addProperties(array $properties): static {
    foreach ($properties as $property) {
      $this->addProperty($property);
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function addProperty(PropertyInterface $property): static {
    $this->properties[$property->getName()] = $property;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getDependencies(): array {
    $dependencies = [];
    foreach ($this->arguments as $argument) {
      $dependencies = array_merge($dependencies, $argument->getDependencies());
    }
    foreach ($this->properties as $property) {
      $dependencies = array_merge($dependencies, $property->getDependencies());
    }
    return $dependencies;
  }

  /**
   * {@inheritdoc}
   */
  public function compile(): string {
    $service = '$service';
    $code = "{$service} = new \\" . $this->class . "(";
    $code .= Indenter::indent($this->compileArguments());
    $code .= ");\n";
    $code .= $this->compileProperties($service);
    $code .= "return {$service};";
    return $code;
  }

  /**
   * Compile the arguments for the service.
   *
   * @return string
   *   The compiled code.
   */
  protected function compileArguments(): string {
    $code = '';
    if ($this->arguments) {
      foreach ($this->arguments as $index => $argument) {
        $code .= $index ? ",\n" : "\n";
        $code .= $argument->compile();
      }
      $code .= "\n";
    }
    return $code;
  }

  /**
   * Compile the properties for the service.
   *
   * @param string $service
   *   The service variable.
   *
   * @return string
   *   The compiled code.
   */
  protected function compileProperties(string $service): string {
    $code = '';
    if ($this->properties) {
      $code .= "\n";
      foreach ($this->properties as $property) {
        $code .= $property->compile($service) . "\n";
      }
      $code .= "\n";
    }
    return $code;
  }

}
