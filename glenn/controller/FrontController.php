<?php
namespace glenn\controller;

use glenn\http\Request,
	glenn\router\Router,
	glenn\router\RouterTree;

class FrontController implements Dispatcher
{
    protected $router;
	
    public function __construct() 
    {
		// Create router here temporary, this shall be done in bootstrap later.
		// Dispatcher only need to be coupled with Router (resolveRoute).
		$router = new RouterTree();

        $this->router = Router::current();
    }
    
	public function dispatch(Request $request)
	{
        try {
			$result = $this->router->resolveRoute($request->uri());

		} catch(\Exception $e) {
			// 404

		}
		
		$class = $result['controller'] . 'Controller';
		$controller = new $class($request);
        $method = $result['action'] . 'Action';
		
        return $controller->{$method}();
	}
}
