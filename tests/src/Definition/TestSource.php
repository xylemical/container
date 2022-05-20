<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition;

use Xylemical\Container\Definition\Modifier\ServiceCollectorModifier;

/**
 * A test source.
 */
class TestSource extends Source {

  /**
   * {@inheritdoc}
   */
  protected function doLoad(): array {
    return [
      'modifiers' => [
        ServiceCollectorModifier::class,
      ],
      'services' => [
        SourceInterface::class => [
          'class' => Source::class,
          'tags' => ['test', ['name' => 'foo']],
        ],
      ],
    ];
  }

}
