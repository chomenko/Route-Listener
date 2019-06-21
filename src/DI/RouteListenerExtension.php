<?php
/**
 * Author: Mykola Chomenko
 * Email: mykola.chomenko@dipcom.cz
 */

namespace Chomenko\RouteListener\DI;

use Chomenko\RouteListener\RouteList;
use Kdyby\Events\EventManager;
use Nette\Application\IRouter;
use Nette\Configurator;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\DI\Statement;
use Nette\PhpGenerator\PhpLiteral;

class RouteListenerExtension extends CompilerExtension
{

	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();
		$routing = $builder->getDefinitionByType(IRouter::class);
		$routing->setFactory(RouteList::class);

		$ref = new \ReflectionClass(RouteList::class);
		foreach ($ref->getProperties() as $property) {
			if (!$property->isPublic()) {
				continue;
			}

			if (!preg_match('#^on[A-Z]#', $property->getName())) {
				continue;
			}

			$routing->addSetup('$' . $property->getName(), [
				new Statement('@' . EventManager::class . '::createEvent', [
					[\Nette\Application\Routers\RouteList::class, $property->getName()],
					new PhpLiteral('$service->' . $property->getName()),
					NULL,
					FALSE
				]),
			]);
		}
	}

	/**
	 * @param Configurator $configurator
	 */
	public static function register(Configurator $configurator)
	{
		$configurator->onCompile[] = function ($config, Compiler $compiler) {
			$compiler->addExtension('RouteListenerExtension', new RouteListenerExtension());
		};
	}

}
