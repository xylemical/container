<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition;

/**
 * Provides a class for applying bulk modifications.
 */
class Modifier extends ModifierBase {

  /**
   * The modifiers.
   *
   * @var \Xylemical\Container\Definition\ModifierInterface[][]
   */
  protected array $modifiers = [];

  /**
   * Get the modifiers.
   *
   * @return \Xylemical\Container\Definition\ModifierInterface[]
   *   The modifier.
   */
  public function getModifiers(): array {
    return array_reduce($this->modifiers, function ($initial, $delta) {
      return array_merge($initial, $delta);
    }, []);
  }

  /**
   * Set the modifiers.
   *
   * @param \Xylemical\Container\Definition\ModifierInterface[] $modifiers
   *   The modifiers.
   *
   * @return $this
   */
  public function setModifiers(array $modifiers): static {
    $this->modifiers = [];
    $this->addModifiers($modifiers);
    return $this;
  }

  /**
   * Add modifiers.
   *
   * @param \Xylemical\Container\Definition\ModifierInterface[] $modifiers
   *   The modifiers.
   *
   * @return $this
   */
  public function addModifiers(array $modifiers): static {
    foreach ($modifiers as $modifier) {
      $this->addModifier($modifier);
    }
    return $this;
  }

  /**
   * Add a modifier.
   *
   * @param \Xylemical\Container\Definition\ModifierInterface $modifier
   *   The modifier.
   *
   * @return $this
   */
  public function addModifier(ModifierInterface $modifier): static {
    $this->modifiers[$modifier->getPriority()][] = $modifier;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function apply(SourceInterface $source): void {
    krsort($this->modifiers);
    foreach ($this->modifiers as $modifiers) {
      foreach ($modifiers as $modifier) {
        $modifier->apply($source);
      }
    }
  }

}
