<?php
/**
 * Author: Mykola Chomenko
 * Email: mykola.chomenko@dipcom.cz
 */

namespace Chomenko\RouteListener;

use Nette;
use Nette\Application\Routers\RouteList as BaseRouteList;

/**
 * @method onMatch(IRouter $router, Nette\Http\IRequest $httpRequest)
 * @method onMatched(IRouter $router, Nette\Http\IRequest $httpRequest, Nette\Application\Request|null $request)
 *
 * @method onConstructUrl(IRouter $router, Nette\Application\Request $appRequest, Nette\Http\Url $refUrl)
 * @method onConstructedUrl(IRouter $router, Nette\Application\Request $appRequest, Nette\Http\Url $refUrl, null|Nette\Http\Url|string $url)
 */
class RouteList extends BaseRouteList
{

	/**
	 * @var callable[]
	 */
	public $onConstructUrl = [];

	/**
	 * @var callable[]
	 */
	public $onConstructedUrl = [];

	/**
	 * @var callable[]
	 */
	public $onMatch = [];

	/**
	 * @var callable[]
	 */
	public $onMatched = [];

	public function __construct($module = NULL)
	{
		parent::__construct($module);
	}

	/**
	 * @param Nette\Http\IRequest $httpRequest
	 * @return Nette\Application\Request|null
	 */
	public function match(Nette\Http\IRequest $httpRequest)
	{
		$this->onMatch($this, $httpRequest);
		$request = parent::match($httpRequest);
		$this->onMatched($this, $httpRequest, $request);
		return $request;
	}

	/**
	 * @param Nette\Application\Request $appRequest
	 * @param Nette\Http\Url $refUrl
	 * @return string|null
	 */
	public function constructUrl(Nette\Application\Request $appRequest, Nette\Http\Url $refUrl)
	{
		$this->onConstructUrl($this, $appRequest, $refUrl);
		$url = parent::constructUrl($appRequest, $refUrl);
		$this->onConstructedUrl($this, $appRequest, $refUrl, $url);
		return $url;
	}

}
