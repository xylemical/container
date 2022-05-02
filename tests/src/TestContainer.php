<?php

declare(strict_types=1);

namespace Xylemical\Container;

/**
 * Provides the container to test behaviour.
 */
class TestContainer extends Container {

  /**
   * Demo services.
   */
  protected const SERVICES = [
    S1::class => 'doS1',
    S2::class => 'doS2',
    S3::class => 'doS3',
  ];

  /**
   * Create service 1.
   *
   * @return object
   *   The service.
   */
  protected function doS1(): object {
    return new S1();
  }

  /**
   * Create service 2.
   *
   * @return object
   *   The service.
   */
  protected function doS2(): object {
    $object = new S2();
    $object->s1 = $this->get(S1::class);
    return $object;
  }

  /**
   * Create service 3.
   *
   * @return object
   *   The service.
   */
  protected function doS3(): object {
    $object = new S3();
    $object->s2 = $this->get(S2::class);
    return $object;
  }

}

/**
 * Dummy service.
 */
class S1 {

}

/**
 * Dummy service.
 */
class S2 {

  /**
   * To check loaded correctly.
   *
   * @var mixed
   */
  public mixed $s1 = NULL;

}

/**
 * Dummy service.
 */
class S3 {

  /**
   * To check loaded correctly.
   *
   * @var mixed
   */
  public mixed $s2 = NULL;

}

/**
 * Dummy service.
 */
class S4 {

}
