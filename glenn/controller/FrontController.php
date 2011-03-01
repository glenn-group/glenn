<?php
namespace glenn\controller;

use glenn\http\Request,
	glenn\http\Response,
	glenn\router\Router,
	glenn\view\View;

class FrontController implements Dispatcher
{
	protected $router;
	
    public function __construct(Router $router)
    {
        $this->router = $router;
    }
    
	public function dispatch(Request $request)
	{
		$result = $this->router->resolveRoute($request);
		
		$class = 'controllers\\' . $result['controller'] . 'Controller';
		$method = $result['action'] . 'Action';
		$controller = new $class(
			$request, new View($result['controller'] . '/' . $result['action'])
		);
		
		$result = $controller->{$method}();
		if ($result instanceof Response) {
			return $result;
		}
		return new Response($controller->view()->render());
	}
	
	public function router()
	{
		return $this->router;
	}
}
