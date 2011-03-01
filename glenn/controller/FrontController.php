<?php
namespace glenn\controller;

use glenn\http\Request,
	glenn\router\Router,
	glenn\router\RouterTree;

use glenn\event\Event,
	glenn\event\EventHandler,
    glenn\http\Request;


class FrontController implements Dispatcher
{
	protected $events;

	protected $router;
	
    public function __construct() 
    {
		// Create router here temporary, this shall be done in bootstrap later.
		// Dispatcher only need to be coupled with Router (resolveRoute).
		$router = new RouterTree();

        $this->router = Router::current();

        $this->events = new EventHandler();

    }
    
	public function dispatch(Request $request)
	{
        try {
			$result = $this->router->resolveRoute($request->uri());

		} catch(\Exception $e) {
			// 404

		}

		$this->events->trigger(new Event($this, 'mvc.routing.pre'));
		$result = array('controller' => 'index', 'action' => 'index');
		$this->events->trigger(new Event($this, 'mvc.routing.post'));

		
		$class = $result['controller'] . 'Controller';
		$controller = new $class($request);
        $method = $result['action'] . 'Action';
		
		$this->events->trigger(new Event($this, 'mvc.dispatching.pre'));
        $response = $controller->{$method}();
		$this->events->trigger(new Event($this, 'mvc.dispatching.post'));
		
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
