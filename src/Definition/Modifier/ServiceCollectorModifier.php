<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition\Modifier;

use Xylemical\Container\Builder\Property\ServiceCollectorPropertyBuilder;
use Xylemical\Container\Definition\ModifierBase;
use Xylemical\Container\Definition\Source;
use function array_merge;
use function array_reduce;
use function intval;
use function krsort;

/**
 * Provides a service collector modifier.
 */
class ServiceCollectorModifier extends ModifierBase {

  /**
   * {@inheritdoc}
   */
  public function apply(Source $source): void {
    $source->addPropertyBuilder(ServiceCollectorPropertyBuilder::class);

    $services = $source->findTaggedServices('service.collector');
    foreach ($services as $service) {
      $tag = $service->getTag('service.collector');

      $service->setProperty('service.collector', [
        'method' => $tag['method'] ?? 'addService',
        'services' => array_map(function ($service) {
          return $service->getName();
        }, $this->getTaggedServices($source, $tag['tag'])),
      ]);
    }
  }

  /**
   * Get the tagged services ordered by priority.
   *
   * @param \Xylemical\Container\Definition\Source $source
   *   The source.
   * @param string $tag
   *   The tag.
   *
   * @return \Xylemical\Container\Definition\ServiceDefinition[]
   *   The services.
   */
  protected function getTaggedServices(Source $source, string $tag): array {
    $services = [];
    foreach ($source->findTaggedServices($tag) as $taggedService) {
      $info = $taggedService->getTag($tag);
      $priority = intval($info['priority'] ?? 0);
      $services[$priority][] = $taggedService;
    }

    krsort($services);
    return array_reduce($services, function ($carry, $item) {
      return array_merge($carry, $item);
    }, []);
  }

}
