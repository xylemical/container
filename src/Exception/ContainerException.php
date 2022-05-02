<?php

declare(strict_types=1);

namespace Xylemical\Container\Exception;

use Psr\Container\ContainerExceptionInterface;

/**
 * Triggers on a container exception.
 */
class ContainerException extends \Exception implements ContainerExceptionInterface {

}
