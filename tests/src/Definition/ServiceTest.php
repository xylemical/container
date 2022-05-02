<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition;

use PHPUnit\Framework\TestCase;
use Xylemical\Container\Definition\Argument\EnvironmentArgument;
use Xylemical\Container\Definition\Argument\ServiceArgument;
use Xylemical\Container\Definition\Argument\ValueArgument;

/**
 * Tests \Xylemical\Container\Definition\Service.
 */
class ServiceTest extends TestCase {

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $service = new Service(ServiceInterface::class, Service::class);
    $this->assertEquals(ServiceInterface::class, $service->getName());
    $this->assertEquals(Service::class, $service->getClass());
    $this->assertEquals([], $service->getArguments());
    $this->assertNull($service->getArgument(0));
    $this->assertEquals([], $service->getProperties());
    $this->assertNull($service->getProperty('property'));
    $this->assertFalse($service->hasProperty('property'));
    $this->assertEquals([], $service->getDependencies());

    $compiled = <<<EOF
\$service = new \Xylemical\Container\Definition\Service();
return \$service;
EOF;

    $this->assertEquals($compiled, $service->compile());
  }

  /**
   * Test the service arguments.
   */
  public function testArguments(): void {
    $service = new Service(ServiceInterface::class, Service::class);
    $value = new ValueArgument('fresh out of the docks.');
    $env = new EnvironmentArgument('OMG', 'foo');
    $arg = new ServiceArgument(PropertyInterface::class);
    $service->setArguments([
      $value,
      $env,
      $arg,
    ]);
    $this->assertEquals([$value, $env, $arg], $service->getArguments());
    $this->assertSame($env, $service->getArgument(1));
    $this->assertEquals([PropertyInterface::class], $service->getDependencies());

    $compiled = <<<EOF
\$service = new \Xylemical\Container\Definition\Service(
  'fresh out of the docks.',
  getenv('OMG') !== FALSE ? getenv('OMG') : 'foo',
  \$this->get('Xylemical\\\\Container\\\\Definition\\\\PropertyInterface')
);
return \$service;
EOF;

    $this->assertEquals($compiled, $service->compile());

    $service->removeArgument(1);
    $this->assertEquals([$value, $arg], $service->getArguments());
  }

  /**
   * Test the service properties.
   */
  public function testProperties(): void {
    $service = new Service(ServiceInterface::class, Service::class);
    $prop = new TestProperty();
    $service->setProperties([
      $prop,
    ]);
    $this->assertEquals(['property' => $prop], $service->getProperties());
    $this->assertSame($prop, $service->getProperty('property'));
    $this->assertTrue($service->hasProperty('property'));
    $this->assertEquals([ServiceInterface::class], $service->getDependencies());

    $compiled = <<<EOF
\$service = new \Xylemical\Container\Definition\Service();

\$service->addProperty('foo', 'bar');

return \$service;
EOF;

    $this->assertEquals($compiled, $service->compile());
  }

}
