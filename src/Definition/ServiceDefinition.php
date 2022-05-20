<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition;

/**
 * Provide a service definition.
 */
class ServiceDefinition {

  /**
   * The class/interface name of the service definition.
   *
   * @var string
   */
  protected string $name;

  /**
   * The service definition.
   *
   * @var array
   */
  protected array $definition;

  /**
   * ServiceDefinition constructor.
   *
   * @param string $name
   *   The class.
   * @param array $definition
   *   The definition.
   */
  public function __construct(string $name, array $definition = []) {
    $this->name = $name;
    $this->definition = $definition + [
      'class' => $name,
      'arguments' => [],
      'tags' => [],
    ];

    $tags = [];
    foreach ($this->definition['tags'] as $tag) {
      if (is_string($tag)) {
        $tags[$tag] = ['name' => $tag];
      }
      elseif (isset($tag['name'])) {
        $tags[$tag['name']] = $tag;
      }
    }
    $this->definition['tags'] = $tags;
  }

  /**
   * Get the name of the service.
   *
   * @return string
   *   The name.
   */
  public function getName(): string {
    return $this->name;
  }

  /**
   * Get the class used for the service.
   *
   * @return string
   *   The class.
   */
  public function getClass(): string {
    return $this->definition['class'] ?? $this->name;
  }

  /**
   * Set the class used for the service.
   *
   * @param string $class
   *   The class.
   *
   * @return $this
   */
  public function setClass(string $class): static {
    $this->definition['class'] = $class;
    return $this;
  }

  /**
   * Get the arguments for the service.
   *
   * @return array
   *   The arguments.
   */
  public function getArguments(): array {
    return $this->definition['arguments'];
  }

  /**
   * Set the arguments.
   *
   * @param array $arguments
   *   The arguments.
   *
   * @return $this
   */
  public function setArguments(array $arguments): static {
    $this->definition['arguments'] = $arguments;
    return $this;
  }

  /**
   * Get the properties of the service.
   *
   * @return array
   *   The properties of the service.
   */
  public function getProperties(): array {
    $definition = $this->definition;
    unset($definition['class'], $definition['arguments'], $definition['tags']);
    return $definition;
  }

  /**
   * Get the property value.
   *
   * @param string $property
   *   The property.
   *
   * @return mixed
   *   The value.
   */
  public function getProperty(string $property): mixed {
    return $this->getProperties()[$property] ?? NULL;
  }

  /**
   * Set multiple property values.
   *
   * @param array $properties
   *   The properties.
   *
   * @return $this
   */
  public function setProperties(array $properties): static {
    foreach ($properties as $property => $value) {
      $this->setProperty($property, $value);
    }
    return $this;
  }

  /**
   * The array keys to filter from the definition.
   *
   * @return array
   *   The keys.
   */
  protected function filterDefinition(): array {
    return ['class', 'arguments', 'tags'];
  }

  /**
   * Set a property value.
   *
   * @param string $property
   *   The name.
   * @param mixed $value
   *   The value.
   *
   * @return $this
   */
  public function setProperty(string $property, mixed $value): static {
    if (!in_array($property, $this->filterDefinition())) {
      $this->definition[$property] = $value;
    }
    return $this;
  }

  /**
   * Check the definition has the property.
   *
   * @param string $property
   *   The property.
   *
   * @return bool
   *   The result.
   */
  public function hasProperty(string $property): bool {
    return !in_array($property, $this->filterDefinition()) && isset($this->definition[$property]);
  }

  /**
   * Get the definition for the service.
   *
   * @return array
   *   The definition.
   */
  public function getDefinition(): array {
    $definition = $this->definition;
    $definition['tags'] = array_values($definition['tags']);
    return $definition;
  }

  /**
   * Get the tag definitions.
   *
   * @return array
   *   The tags.
   */
  public function getTags(): array {
    return array_values($this->definition['tags']);
  }

  /**
   * Get the tag information.
   *
   * @param string $tag
   *   The tag.
   *
   * @return array|null
   *   The tag definition.
   */
  public function getTag(string $tag): ?array {
    return $this->definition['tags'][$tag] ?? NULL;
  }

  /**
   * Check the service has a tag.
   *
   * @param string $tag
   *   The tag.
   *
   * @return bool
   *   The result.
   */
  public function hasTag(string $tag): bool {
    return isset($this->definition['tags'][$tag]);
  }

  /**
   * Add a tag to the definition.
   *
   * @param string $tag
   *   The tag.
   * @param array $definition
   *   Other settings for the tag.
   *
   * @return $this
   */
  public function addTag(string $tag, array $definition = []): static {
    $this->definition['tags'][$tag] = ['name' => $tag] + $definition;
    return $this;
  }

}
