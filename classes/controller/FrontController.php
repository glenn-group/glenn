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
		
		$class = $this->className($result['controller']);
		$method = $this->methodName($result['action']);
		$controller = new $class(
			$request, new View(\lcfirst($result['controller']) . '/' . $result['action'])
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
	
	private function className($controller) 
	{
		return 'app\\controller\\' . \ucfirst($controller) . 'Controller';
	}
	
	private function methodName($action)
	{
		return \lcfirst($action) . 'Action';
	}
}
