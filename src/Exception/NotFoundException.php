<?php

declare(strict_types=1);

namespace Xylemical\Container\Exception;

use Psr\Container\NotFoundExceptionInterface;

/**
 * Triggers when service not found.
 */
class NotFoundException extends \Exception implements NotFoundExceptionInterface {

}
