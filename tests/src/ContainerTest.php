<?php

declare(strict_types=1);

namespace Xylemical\Container;

use PHPUnit\Framework\TestCase;
use Xylemical\Container\Exception\NotFoundException;

/**
 * Tests \Xylemical\Container\Container.
 */
class ContainerTest extends TestCase {

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $container = new TestContainer();
    $this->assertTrue($container->has(S1::class));
    $this->assertFalse($container->has(S4::class));

    $s1 = $container->get(S1::class);

    $s3 = $container->get(S3::class);
    $this->assertSame($s1, $s3->s2->s1);

    $this->assertSame($s1, $container->get(S1::class));

    $container = new TestContainer([S1::class => $s1]);
    $this->assertSame($s1, $container->get(S1::class));
  }

  /**
   * Test container exception.
   */
  public function testException(): void {
    $container = new TestContainer();
    $this->expectException(NotFoundException::class);
    $container->get(S4::class);
  }

}
