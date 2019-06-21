# Route listener

This extension allows you to extend your router

## Required

- [kdyby/events](https://github.com/Kdyby/Events)

## Install

````bash
composer require chomenko/route-listener
````

## Configure

in config.neon
````neon
extensions:
	events: Kdyby\Events\DI\EventsExtension
	#console: Kdyby\Console\DI\ConsoleExtension
	routeListener: Chomenko\RouteListener\DI\RouteListenerExtension
````

## Use

Events
- onMatch
- onMatched
- onConstructUrl
- onConstructedUrl

````php
<?php
namespace App\Listener;

use Kdyby\Events\Subscriber;
use Nette\Application\Routers\RouteList;
use Nette\Application\Request;

class EventRoute implements Subscriber
{
	/**
	 * @return array
	 */
	public function getSubscribedEvents()
	{
		return [
			RouteList::class . "::onConstructUrl" => "onConstructUrl",
		];
	}

	/**
	 * @param IRouter $routerList
	 * @param Request $request
	 */
	public function onConstructUrl(IRouter $routerList, Request $request)
	{
		$parameters = $request->getParameters();
		$parameters["foo"] = "bar";
		$request->setParameters($parameters);
	}
}

````