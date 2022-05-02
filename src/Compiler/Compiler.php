<?php

declare(strict_types=1);

namespace Xylemical\Container\Compiler;

use Twig\Environment;
use Xylemical\Code\Definition\Constant;
use Xylemical\Code\Definition\File;
use Xylemical\Code\Definition\Method;
use Xylemical\Code\Definition\Structure;
use Xylemical\Code\Expression;
use Xylemical\Code\Php\Writer\Twig\PhpTwigExtension;
use Xylemical\Code\Php\Writer\Twig\PhpTwigLoader;
use Xylemical\Code\Visibility;
use Xylemical\Code\Writer\Twig\TwigWriter;
use Xylemical\Container\Container;
use Xylemical\Container\Definition\DefinitionInterface;
use Xylemical\Container\Definition\ServiceInterface;
use Xylemical\Container\Exception\CyclicDefinitionException;

/**
 * Provides compilation of a definition into a source file.
 */
class Compiler {

  /**
   * Perform the compilation of the definition.
   *
   * @param \Xylemical\Container\Definition\DefinitionInterface $definition
   *   The container definition.
   *
   * @return string
   *   The compiled container.
   *
   * @throws \Xylemical\Container\Exception\CyclicDefinitionException
   */
  public function compile(DefinitionInterface $definition): string {
    $this->doCalculateDependencies($definition);

    $file = $this->doBuildServices($definition);

    $loader = new PhpTwigLoader();
    $twig = new Environment($loader);
    $twig->addExtension(new PhpTwigExtension());
    $engine = new TwigWriter($twig);

    return $engine->write($file);
  }

  /**
   * Calculate the dependencies for definition services.
   *
   * @param \Xylemical\Container\Definition\DefinitionInterface $definition
   *   The definition.
   *
   * @throws \Xylemical\Container\Exception\CyclicDefinitionException
   */
  protected function doCalculateDependencies(DefinitionInterface $definition): void {
    $dependencies = [];
    foreach ($definition->getServices() as $service) {
      $this->calculateDependencies($definition, $service, $dependencies);
    }
  }

  /**
   * Calculate the dependencies of a service.
   *
   * @param \Xylemical\Container\Definition\DefinitionInterface $definition
   *   The definition.
   * @param \Xylemical\Container\Definition\ServiceInterface|null $service
   *   The service.
   * @param string[][] $dependencies
   *   The dependencies per service.
   *
   * @return bool
   *   Success on calculating the dependency.
   *
   * @throws \Xylemical\Container\Exception\CyclicDefinitionException
   */
  protected function calculateDependencies(DefinitionInterface $definition, ?ServiceInterface $service, array &$dependencies): bool {
    if (!$service || isset($dependencies[$service->getName()])) {
      return FALSE;
    }

    $name = $service->getName();
    $dependencies[$name] = $service->getDependencies();
    foreach ($service->getDependencies() as $dependency) {
      if (!isset($dependencies[$dependency])) {
        $this->calculateDependencies($definition, $definition->getService($dependency), $dependencies);
      }

      if (in_array($name, $dependencies[$dependency])) {
        throw new CyclicDefinitionException();
      }

      $dependencies[$name] = array_merge($dependencies[$name], $dependencies[$dependency]);
    }

    return TRUE;
  }

  /**
   * Build the container definition.
   *
   * @param \Xylemical\Container\Definition\DefinitionInterface $definition
   *   The container definition.
   *
   * @return \Xylemical\Code\Definition\File
   *   The structure.
   */
  protected function doBuildServices(DefinitionInterface $definition): File {
    $file = new File('container.php');
    $manager = $file->getNameManager();

    $const = [];
    $methods = [];
    foreach (array_values($definition->getServices()) as $index => $service) {
      $name = 'getS' . $index;
      $const[$service->getName()] = $name;
      $methods[$name] = Method::create($name, $manager)
        ->setType($service->getClass())
        ->setValue(new Expression($service->compile()));
    }

    return $file->addStructure(
      Structure::create($definition->getClass(), $manager)
        ->setParent(Container::class)
        ->addElement(
          Constant::create('SERVICES', $manager)
            ->setVisibility(Visibility::PROTECTED)
            ->setValue(new Expression(var_export($const, TRUE)))
        )
        ->addElements($methods)
    );
  }

}
