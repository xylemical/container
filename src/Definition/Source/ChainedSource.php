<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition\Source;

use Xylemical\Container\Definition\AbstractSource;
use Xylemical\Container\Definition\Definition;
use function array_merge;
use function array_unique;
use function max;

/**
 * Provides a chained source.
 */
class ChainedSource extends AbstractSource {

  /**
   * The sources.
   *
   * @var \Xylemical\Container\Definition\SourceInterface[]
   */
  protected array $sources;

  /**
   * ChainedSource constructor.
   *
   * @param \Xylemical\Container\Definition\SourceInterface[] $sources
   *   The sources.
   */
  public function __construct(array $sources) {
    $this->sources = $sources;
  }

  /**
   * {@inheritdoc}
   */
  public function load(): static {
    foreach ($this->sources as $source) {
      $source->load();
      $this->class = $this->class === Definition::class ? $source->getClass() : $this->class;
      $this->services += $source->getServices();
      $this->serviceBuilders = $this->mergeArray($this->serviceBuilders, $source->getServiceBuilders());
      $this->propertyBuilders = $this->mergeArray($this->propertyBuilders, $source->getPropertyBuilders());
      $this->argumentBuilders = $this->mergeArray($this->argumentBuilders, $source->getArgumentBuilders());
      $this->modifiers = $this->mergeArray($this->modifiers, $source->getModifiers());
      $this->timestamp = max($this->timestamp, $source->getTimestamp());
    }
    return $this;
  }

  /**
   * Merge two arrays.
   *
   * @param array $a
   *   The first array.
   * @param array $b
   *   The second array.
   *
   * @return array
   *   The result.
   */
  protected function mergeArray(array $a, array $b): array {
    return array_values(array_unique(array_merge($a, $b)));
  }

}
