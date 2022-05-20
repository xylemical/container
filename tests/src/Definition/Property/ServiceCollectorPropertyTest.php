<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition\Property;

use PHPUnit\Framework\TestCase;
use Xylemical\Container\Definition\ServiceInterface;
use Xylemical\Container\Definition\SourceInterface;

/**
 * Tests \Xylemical\Container\Definition\ServiceCollectorProperty.
 */
class ServiceCollectorPropertyTest extends TestCase {

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $property = new ServiceCollectorProperty('addService', [
      SourceInterface::class,
      ServiceInterface::class,
    ]);

    $result = "\$service->addService(\$this->get('Xylemical\\\\Container\\\\Definition\\\\SourceInterface'));\n";
    $result .= "\$service->addService(\$this->get('Xylemical\\\\Container\\\\Definition\\\\ServiceInterface'));\n";
    $this->assertEquals($result, $property->compile('$service'));
    $this->assertEquals([
      SourceInterface::class,
      ServiceInterface::class,
    ], $property->getDependencies());

  }

}
