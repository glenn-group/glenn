<?php
namespace glenn\controller\dispatcher;

use glenn\controller\Dispatcher,
    glenn\controller\Controller,
    glenn\event\Event,
    glenn\event\EventHandler,
    glenn\http\Request,
	glenn\http\Response,
	glenn\router\Router;

class InternalDispatcher implements Dispatcher
{
    /**
     * @var string
     */
    protected $actionSuffix = 'Action';
	
    /**
     * @var string
     */
	protected $controllerSuffix = 'Controller';
    
    /**
     * @var EventHandler
     */
    protected $events;
    
    /**
     * @var Router
     */
	protected $router;
	
    /**
     * @param Router $router 
     */
    public function __construct(Router $router)
    {
        $this->events = new EventHandler();
        $this->router = $router;
    }
    
    /**
     *
     * @param  Request $request
     * @return Response 
     */
	public function dispatch(Request $request)
	{
		if (\strpos($request->uri(), 'http://') === 0) {
			$dispatcher = new ExternalDispatcher();
			return $dispatcher->dispatch($request);
		}
		
        $this->events()->trigger(new Event($this, 'glenn.routing.before'));
        try {
            $result = new \stdClass();
            $result->controller = 'app\\controller\\indexController';
            $result->action     = 'indexAction';
        } catch (Exception $e) {
            return Response::notFound();
        }
        $this->events()->trigger(new Event($this, 'glenn.routing.after'));
        
		$controller = Controller::factory(
			$result->controller, $this, $request
		);
        
		
		$responses = $this->events()->triggerUntil(
			new Event($controller, 'glenn.dispatching.before'),
			function($response) {
				return $response instanceof Response;
			},
			function ($response) {
				return $response instanceof Response;
			}
		);
		if (\count($responses) > 0) {
			return $responses[0];
		}
		
		
		\call_user_func(array($controller, $result->action));
		
		
		$responses = $this->events()->trigger(
			new Event($controller, 'glenn.dispatching.after'), 
			function($response) {
				return $response instanceof Response;
			}
		);
		if (\count($responses) > 0) {
			return $responses[\count($responses)];
		}
		
		
	}
	
    /**
     *
     * @return EventHandler
     */
    public function events() 
    {
        return $this->events;
    }
    
    /**
     *
     * @return Router
     */
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
