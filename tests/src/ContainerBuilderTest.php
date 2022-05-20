<?php

declare(strict_types=1);

namespace Xylemical\Container;

use App\Container;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Xylemical\Container\Definition\Definition;
use Xylemical\Container\Definition\Service;
use Xylemical\Container\Definition\ServiceDefinition;
use Xylemical\Container\Definition\ServiceInterface;
use Xylemical\Container\Definition\Source;
use Xylemical\Container\Definition\SourceInterface;
use Xylemical\Container\Exception\ContainerException;

/**
 * Tests \Xylemical\Container\ContainerBuilder.
 */
class ContainerBuilderTest extends TestCase {

  use ProphecyTrait;

  /**
   * The virtual file system.
   *
   * @var \org\bovigo\vfs\vfsStreamDirectory
   */
  protected vfsStreamDirectory $root;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    $this->root = vfsStream::setup();
  }

  /**
   * Create a mock source.
   *
   * @param array $services
   *   The definition.
   * @param int $timestamp
   *   The timestamp.
   *
   * @return \Xylemical\Container\Definition\SourceInterface
   *   The source.
   */
  protected function getMockSource(array $services, int $timestamp): SourceInterface {
    $source = $this->prophesize(SourceInterface::class);
    $source->getClass()->willReturn(Definition::class);
    $source->getTimestamp()->willReturn($timestamp);
    $source->getServices()->willReturn($services);
    $source->getServiceBuilders()->willReturn([]);
    $source->getArgumentBuilders()->willReturn([]);
    $source->getPropertyBuilders()->willReturn([]);
    return $source->reveal();
  }

  /**
   * Tests the sanity of the container build.
   */
  public function testSanity(): void {
    /* @phpstan-ignore-next-line */
    $class = Container::class;
    $filename = "{$this->root->url()}/container.php";

    $definition = [];
    $source = $this->getMockSource($definition, time());

    $builder = new ContainerBuilder($source, $filename, $class);
    $container = $builder->getContainer();

    $expected = file_get_contents(__DIR__ . '/../fixtures/TestSanity.php');
    $this->assertEquals($expected, file_get_contents($filename));
    $this->assertInstanceOf($class, $container);

    $definition = [
      (new ServiceDefinition(ServiceInterface::class, [
        'class' => Service::class,
        'arguments' => ['@' . SourceInterface::class],
      ])),
      (new ServiceDefinition(SourceInterface::class, ['class' => Source::class])),
    ];

    $source = $this->getMockSource($definition, time() - 100);

    $builder = new ContainerBuilder($source, $filename, $class);
    $container = $builder->getContainer();

    $expected = file_get_contents(__DIR__ . '/../fixtures/TestSanity.php');
    $this->assertEquals($expected, file_get_contents($filename));
    $this->assertInstanceOf($class, $container);

    $source = $this->getMockSource($definition, time() + 100);

    $builder = new ContainerBuilder($source, $filename, $class);
    $container = $builder->getContainer();

    $expected = file_get_contents(__DIR__ . '/../fixtures/TestCompilation.php');
    $this->assertEquals($expected, file_get_contents($filename));
    $this->assertInstanceOf($class, $container);

    // Check we don't try to reload the same container name, when it's already
    // been loaded.
    $this->assertFalse($container->has(SourceInterface::class));
  }

  /**
   * Tests write failure.
   */
  public function testWriteFailure(): void {
    $class = 'App\\Container';
    $filename = "{$this->root->url()}/container.php";

    $definition = [];
    $source = $this->getMockSource($definition, time());
    $builder = new ContainerBuilder($source, $filename, $class);
    $builder->getContainer();

    chmod($filename, 0400);

    $this->expectException(ContainerException::class);
    $source = $this->getMockSource($definition, time() + 100);
    $builder = new ContainerBuilder($source, $filename, $class);
    $builder->getContainer();
  }

}
