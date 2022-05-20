<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition\Modifier;

use Xylemical\Container\Builder\Property\ServiceCollectorPropertyBuilder;
use Xylemical\Container\Definition\ModifierBase;
use Xylemical\Container\Definition\SourceInterface;

/**
 * Provides a service collector modifier.
 */
class ServiceCollectorModifier extends ModifierBase {

  /**
   * {@inheritdoc}
   */
  public function apply(SourceInterface $source): void {
    $source->addPropertyBuilder(ServiceCollectorPropertyBuilder::class);

    $services = $source->findTaggedServices('service.collector');
    foreach ($services as $service) {
      $tag = $service->getTag('service.collector');
      $service->setProperty('service.collector', [
        'method' => $tag['method'] ?? 'addService',
        'services' => array_map(function ($service) {
          return $service->getName();
        }, $source->findTaggedServices($tag['tag'])),
      ]);
    }
  }

}
