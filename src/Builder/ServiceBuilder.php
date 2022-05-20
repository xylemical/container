<?php

declare(strict_types=1);

namespace Xylemical\Container\Builder;

use Xylemical\Container\Builder\Service\GenericServiceBuilder;
use Xylemical\Container\Definition\ServiceDefinition;
use Xylemical\Container\Definition\ServiceInterface;

/**
 * Provides a generic service builder.
 */
class ServiceBuilder extends ServiceBuilderBase {

  /**
   * The service builders.
   *
   * @var \Xylemical\Container\Builder\ServiceBuilderInterface[]
   */
  protected array $builders = [];

  /**
   * The default generic service builder.
   *
   * @var \Xylemical\Container\Builder\ServiceBuilderInterface
   */
  protected ServiceBuilderInterface $defaultBuilder;

  /**
   * {@inheritdoc}
   */
  public function __construct() {
    parent::__construct();
    $this->defaultBuilder = new GenericServiceBuilder();
  }

  /**
   * Set the builders.
   *
   * @param \Xylemical\Container\Builder\ServiceBuilderInterface[] $builders
   *   The builders.
   *
   * @return $this
   */
  public function setBuilders(array $builders): static {
    $this->builders = [];
    $this->addBuilders($builders);
    return $this;
  }

  /**
   * Add service builders.
   *
   * @param \Xylemical\Container\Builder\ServiceBuilderInterface[] $builders
   *   The builders.
   *
   * @return $this
   */
  public function addBuilders(array $builders): static {
    foreach ($builders as $builder) {
      $this->addBuilder($builder);
    }
    return $this;
  }

  /**
   * Add an service builder.
   *
   * @param \Xylemical\Container\Builder\ServiceBuilderInterface $builder
   *   The builder.
   *
   * @return $this
   */
  public function addBuilder(ServiceBuilderInterface $builder): static {
    $this->builders[] = $builder;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function applies(ServiceDefinition $service): bool {
    foreach ($this->builders as $builder) {
      if ($builder->applies($service)) {
        return TRUE;
      }
    }
    return $this->defaultBuilder->applies($service);
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Xylemical\Container\Exception\InvalidDefinitionException
   */
  public function build(ServiceDefinition $service, BuilderInterface $builder): ServiceInterface {
    foreach ($this->builders as $serviceBuilder) {
      if ($serviceBuilder->applies($service)) {
        return $serviceBuilder->build($service, $builder);
      }
    }
    return $this->defaultBuilder->build($service, $builder);
  }

}
