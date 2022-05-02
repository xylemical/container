<?php

declare(strict_types=1);

namespace App;

use Xylemical\Container\Definition\Service;
use Xylemical\Container\Source\Source;
use Xylemical\Container\Container as ContainerContainer;


class Container extends ContainerContainer {

    protected const SERVICES = array (
      'Xylemical\\Container\\Definition\\ServiceInterface' => 'getS0',
      'Xylemical\\Container\\Source\\SourceInterface' => 'getS1',
    );

    public function getS0(): Service {
        $service = new \Xylemical\Container\Definition\Service(
          $this->get('Xylemical\\Container\\Source\\SourceInterface')
        );
        return $service;
    }

    public function getS1(): Source {
        $service = new \Xylemical\Container\Source\Source();
        return $service;
    }

}
