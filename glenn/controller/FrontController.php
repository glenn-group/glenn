<?php
namespace glenn\controller;

use glenn\http\Request;

class FrontController implements Dispatcher
{
    protected $router;
	
    public function __construct() 
    {
        //$this->router = new Router_Tree();
    }
    
	public function dispatch(Request $request)
	{
        //$result = $this->router->route($request->uri());
		$result = array('controller' => 'index', 'action' => 'index');
		
		$class = \ucfirst($result['controller']) . 'Controller';
		$controller = new $class($request);
        $method = \lcfirst($result['action']) . 'Action';
		
        return $controller->{$method}();
	}
}