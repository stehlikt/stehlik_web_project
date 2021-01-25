<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;

        $router->withModule('Admin')
            ->addRoute('admin','Admin:default');

        $router->addRoute('registrace','Register:default');
        $router->addRoute('prihlaseni','Sign:in');
        $router->addRoute('prispevky','Posts:default');
        $router->addRoute('dynamicke-menu','MenuItems:default');
		$router->addRoute('<presenter>/<action>[/<id>]', 'Homepage:default');

		return $router;

	}
}
