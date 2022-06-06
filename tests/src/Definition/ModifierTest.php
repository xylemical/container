<?php

declare(strict_types=1);

namespace Xylemical\Container\Definition;

use PHPUnit\Framework\TestCase;

/**
 * Tests \Xylemical\Container\Source\Modifier.
 */
class ModifierTest extends TestCase {

  /**
   * Test sanity.
   */
  public function testSanity(): void {
    $modifier = new Modifier();
    $this->assertEquals(0, $modifier->getPriority());
    $this->assertEquals([], $modifier->getModifiers());

    $a = new TestModifier();
    $a->name = 'a';
    $a->priority = -1;
    $b = new TestModifier();
    $b->name = 'b';
    $c = new TestModifier();
    $c->name = 'c';
    $d = new TestModifier();
    $d->priority = 1;
    $d->name = 'd';

    $modifier->setModifiers([$a, $b, $c, $d]);
    $this->assertEquals([$a, $b, $c, $d], $modifier->getModifiers());

    $source = $this->getMockBuilder(Source::class)->getMock();
    $modifier->apply($source);
    // @phpstan-ignore-next-line
    $this->assertEquals(['d', 'b', 'c', 'a'], $source->modifier);
  }

}
