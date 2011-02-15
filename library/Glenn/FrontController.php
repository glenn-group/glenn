<?php
namespace Glenn;

use Application\IndexController;

class FrontController implements Dispatchable
{
	public function dispatch(Request $request)
	{
		switch ($request->uri) {
		    case "/":
		        $controller = new IndexController();
		        return $controller->indexAction();
		    case "/about":
		        $controller = new IndexController();
		        return $controller->aboutAction();
		    default:
		        return new Response("Page not found", 404);
		}
	}
}