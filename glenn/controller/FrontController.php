<?php
namespace glenn\controller;

use glenn\http\Request,
	glenn\router\Router;

class FrontController implements Dispatcher
{
	protected $router;
	
    public function __construct(Router $router)
    {
        $this->router = $router;
    }
    
	public function dispatch(Request $request)
	{
		//$result = $this->router->resolveRoute($request);
		$result = array('controller' => 'blog', 'action' => 'index');
		
		$class = 'controllers\\' . \ucfirst($result['controller']) . 'Controller';
		$controller = new $class($request);
        $method = \lcfirst($result['action']) . 'Action';
		
        $response = $controller->{$method}();
		
		return $response;
	}
	
	public function events()
	{
		return $this->events;
	}
	
	public function router()
	{
		return $this->router;
	}
}
