<?php
namespace glenn\controller;

use glenn\http\interfaces\Request,
	glenn\http\interfaces\Response as IResponse,
	glenn\router\Router,
	_\view\View,
	glenn\http\Response;

class FrontController implements Dispatcher
{
	protected $router;
	
    public function __construct(Router $router)
    {
        $this->router = $router;
    }
    
	public function dispatch(Request $request)
	{
		$result = $this->router->route($request);
		
		$class = $this->className($result['controller']);
		$method = $this->methodName($result['action']);
		$action = \lcfirst($result['controller']) . '/' . $result['action'];
		$view = new View($action);
		$controller = new $class(
			$request, $view
		);
		
		$result = $controller->{$method}();
		if ($result instanceof IResponse) {
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
		return 'app\controller\\' . \ucfirst($controller) . 'Controller';
	}
	
	private function methodName($action)
	{
		return \lcfirst($action) . 'Action';
	}
}
