<?php

declare(strict_types=1);

namespace Xylemical\Container;

use Psr\Container\ContainerInterface;

/**
 * Provides root container support.
 */
interface ContainerDecoratedInterface extends ContainerInterface {

  /**
   * Set the root container.
   *
   * @param \Psr\Container\ContainerInterface|null $container
   *   The container.
   *
   * @return $this
   */
  public function setRoot(?ContainerInterface $container): static;

}
